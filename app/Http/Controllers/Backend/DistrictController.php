<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DistrictController extends Controller
{

    public function districtManage(Request $req) {
        if ($req->ajax()) {
            $data = DB::table('districts')->select('id', 'name', 'created_at');

            return DataTables::of($data)
            ->addIndexColumn() // ক্রমিক সংখ্যা
            ->addColumn('action', function($row){
                $editUrl = route('district.edit', $row->id); // route + row id
                $deleteUrl = route('district.delete', $row->id); // যদি তুমি delete route বানাও

                return '<a href="'. $editUrl .'" class="btn btn-sm btn-warning"><i class="fa fa-edit text-black"></i></a> 
                        <a href="'. $deleteUrl .'" class="btn btn-sm btn-danger"><i class="fa fa-trash text-white"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);

        }


        return view('backend.district.manage');
    }


    public function districtCreate(Request $req){

        return view('backend.district.add');

    }


    public function districtUpload(Request $req) {
        $req->validate([
            'distName' => 'required|string|unique:districts,name',
        ], [
            'distName.required' => 'জেলার নাম অবশ্যই দিতে হবে।',
            'distName.unique'   => 'এই জেলা ইতিমধ্যেই আছে।',
        ]);

        // Insert
        $status = DB::table('districts')->insert([
            'name' => $req->distName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($status) {
           return redirect()->route('district.manage')->with('success', 'নতুন জেলা সফলভাবে যোগ করা হয়েছে।');

        } else {
            return back()
                ->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')
                ->withInput();
        }
    }



    public function districtEdit($id){

        $data = DB::table('districts')->where('id', $id)->first();
        return view('backend.district.edit' , compact('data'));

    }


    public function districtUpdate(Request $req){
        $req->validate([
            'distName' => 'required|string|unique:districts,name,' . $req->id,
        ], [
            'distName.required' => 'জেলার নাম অবশ্যই দিতে হবে।',
            'distName.unique'   => 'এই জেলা ইতিমধ্যেই আছে।',
        ]);

        $updated = DB::table('districts')
            ->where('id', $req->id)
            ->update([
                'name' => $req->distName,
                'updated_at' => now(),
            ]);

        if ($updated) {
           return redirect()->route('district.manage')->with('success', 'জেলা সফলভাবে আপডেট হয়েছে।');

        } else {
            return back()->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')->withInput();
        }
    }


    public function districtDelete($id) {
        try {
            $deleted = DB::table('districts')->where('id', $id)->delete();

            if ($deleted) {
                return redirect()->back()->with('success', 'জেলা সফলভাবে মুছে ফেলা হয়েছে।');
            } else {
                return redirect()->back()->with('error', 'জেলা খুঁজে পাওয়া যায়নি বা মুছে ফেলা যায়নি।');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।');
        }
    }




}

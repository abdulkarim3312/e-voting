<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DesignationController extends Controller
{
    public function designationManage(Request $req) {
        if ($req->ajax()) {
            $data = DB::table('designations')->select('id', 'name', 'status', 'created_at');

            return DataTables::of($data)
            ->addIndexColumn() 
            ->addColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';
                return '<input type="checkbox" class="status-toggle big-checkbox" data-id="' . $row->id . '" ' . $checked . '>';
            })

            ->addColumn('created_at', function ($row) {
                if ($row->created_at) {
                    return \Carbon\Carbon::parse($row->created_at)
                        ->timezone('Asia/Dhaka')
                        ->format('d M Y, h:i A');
                }
                return '';
            })
            ->addColumn('action', function($row){
                $editUrl = route('designation.edit', $row->id); 
                 $deleteUrl = route('designation.delete', $row->id); // যদি তুমি delete route বানাও
               return '<a href="'. $editUrl .'" class="btn btn-sm btn-warning"><i class="fa fa-edit text-black"></i></a> 
                        <a href="'. $deleteUrl .'" class="btn btn-sm btn-danger"><i class="fa fa-trash text-white"></i></a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);

        }

        return view('backend.designation.manage');
    }


    public function designationCreate(){
        return view('backend.designation.add');
    }


    public function designationUpload(Request $req) {
        $req->merge([
            'name' => preg_replace('/\s+/', ' ', trim($req->input('name')))
        ]);
        $req->validate([
            'name' => 'required|string|unique:designations,name',
        ], [
            'name.required' => 'পদবী নাম অবশ্যই দিতে হবে।',
            'name.unique'   => 'এই পদবী ইতিমধ্যেই আছে।',
        ]);

        // Insert
        $status = DB::table('designations')->insert([
            'name' => $req->name,
            'created_at' => now(),
            'updated_at' => now(),
            'status' => $req->status,
        ]);
    

        if ($status) {
           return redirect()->back('designation.manage')->with('success', 'নতুন পদবী সফলভাবে যোগ করা হয়েছে।');

        } else {
            return back()
                ->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')
                ->withInput();
        }
    }



    public function designationEdit($id){

        $data = DB::table('designations')->where('id', $id)->first();
        return view('backend.designation.edit' , compact('data'));

    }


    public function designationUpdate(Request $req){
        $req->merge([
            'name' => preg_replace('/\s+/', ' ', trim($req->input('name')))
        ]);
        $req->validate([
            'name' => 'required|string|unique:designations,name,' . $req->id,
        ], [
            'name.required' => 'পদবী নাম অবশ্যই দিতে হবে।',
            'name.unique'   => 'এই পদবী ইতিমধ্যেই আছে।',
        ]);

        $updated = DB::table('designations')
            ->where('id', $req->id)
            ->update([
                'name' => $req->name,
                'updated_at' => now(),
                'status' => $req->status,
            ]);

        if ($updated) {
           return redirect()->route('designation.manage')->with('success', 'জেলা সফলভাবে আপডেট হয়েছে।');

        } else {
            return back()->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')->withInput();
        }
    }


    public function designationDelete($id) {
        try {
            $deleted = DB::table('designations')->where('id', $id)->delete();

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

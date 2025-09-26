<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function categoryManage(Request $req) {
        if ($req->ajax()) {
            $data = DB::table('categories')->select('id', 'name', 'status', 'created_at');

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
                $editUrl = route('category.edit', $row->id); 
                 $deleteUrl = route('category.delete', $row->id); // যদি তুমি delete route বানাও
               return '<a href="'. $editUrl .'" class="btn btn-sm btn-warning"><i class="fa fa-edit text-black"></i></a> 
                        <a href="'. $deleteUrl .'" class="btn btn-sm btn-danger"><i class="fa fa-trash text-white"></i></a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);

        }

        return view('backend.category.manage');
    }


    public function categoryCreate(){
        return view('backend.category.add');
    }


    public function categoryUpload(Request $req) {
        $req->validate([
            'name' => 'required|string|unique:categories,name',
        ], [
            'name.required' => 'জেলার নাম অবশ্যই দিতে হবে।',
            'name.unique'   => 'এই জেলা ইতিমধ্যেই আছে।',
        ]);

        // Insert
        $status = DB::table('categories')->insert([
            'name' => $req->name,
            'created_at' => now(),
            'updated_at' => now(),
            'status' => $req->status,
            'slug' => Str::slug($req->name) . '-' . Str::lower(Str::random(6))
        ]);
    

        if ($status) {
           return redirect()->route('category.manage')->with('success', 'নতুন জেলা সফলভাবে যোগ করা হয়েছে।');

        } else {
            return back()
                ->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')
                ->withInput();
        }
    }



    public function categoryEdit($id){

        $data = DB::table('categories')->where('id', $id)->first();
        return view('backend.category.edit' , compact('data'));

    }


    public function categoryUpdate(Request $req){
        $req->validate([
            'name' => 'required|string|unique:categories,name,' . $req->id,
        ], [
            'name.required' => 'জেলার নাম অবশ্যই দিতে হবে।',
            'name.unique'   => 'এই জেলা ইতিমধ্যেই আছে।',
        ]);

        $updated = DB::table('categories')
            ->where('id', $req->id)
            ->update([
                'name' => $req->name,
                'updated_at' => now(),
                'status' => $req->status,
                'slug' => Str::slug($req->name) . '-' . Str::lower(Str::random(6))
            ]);

        if ($updated) {
           return redirect()->route('category.manage')->with('success', 'জেলা সফলভাবে আপডেট হয়েছে।');

        } else {
            return back()->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')->withInput();
        }
    }


    public function categoryDelete($id) {
        try {
            $deleted = DB::table('categories')->where('id', $id)->delete();

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

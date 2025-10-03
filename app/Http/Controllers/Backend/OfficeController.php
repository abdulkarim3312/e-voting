<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OfficeController extends Controller
{
    

    public function officeManage(Request $req) {
        $query = DB::table('offices')
            ->join('districts', 'offices.district_id', '=', 'districts.id')
            ->select('offices.id', 'offices.office_name', 'districts.name as district_name', 'offices.created_at');

        if ($req->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    if ($row->created_at) {
                        return \Carbon\Carbon::parse($row->created_at)
                            ->timezone('Asia/Dhaka')
                            ->diffForHumans();
                    }
                    return '';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('office.edit', $row->id);
                    $deleteUrl = route('office.delete', $row->id);

                    return '<a href="'. $editUrl .'" class="btn btn-sm btn-warning"><i class="fa fa-edit text-black"></i></a> 
                            <a href="'. $deleteUrl .'" class="btn btn-sm btn-danger"><i class="fa fa-trash text-white"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("backend.office.manage");
    }


    public function officeCreate(Request $req){

        $districts = DB::table("districts")->get();

        return view("backend.office.add" , compact("districts"));
    }


    public function officeUpload(Request $req){
        
        $upload = DB::table("offices")->insert([
            "office_name" => $req->officeName,
            "district_id" => $req->district_id,
            "created_at" => now(),
        ]);

        if($upload){
            return redirect()->route("office.manage")->with('success', 'কর্মস্থল সফলভাবে যোগ করা হয়েছে');
        }else{
            return redirect()->back()->with('success', 'কর্মস্থল যোগ করা যায়নি, আবার চেষ্টা করুন');
        }

    }



    public function officeEdit($id){

        $office = DB::table("offices")->where("id", $id)->first();
        $districts = DB::table("districts")->get();

        return view("backend.office.edit" , compact("office", "districts"));
    }


    public function officeUpdate(Request $req){

        $update = DB::table("offices")->where("id", $req->office_id)->update([
            "office_name" => $req->officeName,
            "district_id" => $req->district_id,
            "updated_at" => now(),
        ]);

        if($update){
            return redirect()->route("office.manage")->with('success', 'কর্মস্থল সফলভাবে আপডেট করা হয়েছে');
        }else{
            return redirect()->back()->with('success', 'কর্মস্থল আপডেট করা যায়নি, আবার চেষ্টা করুন');
        }

    }


    public function officeDelete($id){

        $delete = DB::table("offices")->where("id", $id)->delete();

        if($delete){
            return redirect()->route("office.manage")->with('success', 'কর্মস্থল সফলভাবে মুছে ফেলা হয়েছে');
        }else{
            return redirect()->back()->with('success', 'কর্মস্থল মুছে ফেলা যায়নি, আবার চেষ্টা করুন');
        }

    }

}

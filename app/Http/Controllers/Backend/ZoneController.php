<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
class ZoneController extends Controller
{
    
   public function zoneManage(Request $req) {
        $query = DB::table('zone')->select('id','name','districts','created_at');

        if($req->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editUrl = route('zone.edit', $row->id); // route + row id
                    $deleteUrl = route('zone.delete', $row->id); // যদি তুমি delete route বানাও

                    return '<a href="'. $editUrl .'" class="btn btn-sm btn-warning"><i class="fa fa-edit text-black"></i></a> 
                            <a href="'. $deleteUrl .'" class="btn btn-sm btn-danger"><i class="fa fa-trash text-white"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Backend.zone.manage');
    }


    public function zoneCreate (Request $req){

        $list = DB::table('districts')->get('name');
        return view("Backend.zone.add" , compact("list"));  

    }


    public function zoneUpload (Request $req){
         $req->validate([
            'zoneName' => 'required|string|unique:zone,name',
            'districts' => 'required',
        ], [
            'zoneName.required' => 'জোনের নাম অবশ্যই দিতে হবে।',
            'zoneName.unique'   => 'এই জোন ইতিমধ্যেই আছে।',
        ]);

        // Insert
        $status = DB::table('zone')->insert([
            'name' => $req->zoneName,
            'districts' => $req->districts,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($status) {
           return redirect()->route('zone.manage')->with('success', 'নতুন জেলা সফলভাবে যোগ করা হয়েছে।');

        } else {
            return back()
                ->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')
                ->withInput();
        }
    }


    public function zoneEdit ($id){
        $list = DB::table('districts')->get('name');
        $data = DB::table('zone')->where('id', $id)->first();
        return view('Backend.zone.edit', compact('data' , 'list'));

    }


    public function zoneUpdate (Request $req){

        $updated = DB::table('zone')
            ->where('id', $req->id)
            ->update([
                'name' => $req->zoneName,
                'districts' => $req->districts,
                'updated_at' => now(),
            ]);

        if ($updated) {
           return redirect()->route('zone.manage')->with('success', 'জেলা সফলভাবে আপডেট হয়েছে।');

        } else {
            return back()->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')->withInput();
        }


    }


    public function zoneDelete ($id){
        $action = DB::table('zone')->where('id', $id)->delete();
        if ($action) {
           return redirect()->route('zone.manage')->with('success', 'জেলা সফলভাবে আপডেট হয়েছে।');

        } else {
            return back()->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')->withInput();
        }
    }


}

<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CandidateController extends Controller
{


    public function candidateManage(Request $req) {
        if ($req->ajax()) {
            $data = DB::table('candidates')
                ->leftJoin('categories', 'candidates.category_id', '=', 'categories.id')
                ->leftJoin('employees', 'candidates.employee_id', '=', 'employees.id')
                ->select(
                    'candidates.*',
                    'categories.name as category_name',
                    'employees.name as employee_name',
                    'employees.nid'
                );

            return DataTables::of($data)
                ->filter(function ($query) use ($req) {
                    if ($search = $req->get('search')['value']) {
                        $query->where(function ($q) use ($search) {
                            $q->where('categories.name', 'like', "%{$search}%")
                            ->orWhere('employees.name', 'like', "%{$search}%")
                            ->orWhere('employees.nid', 'like', "%{$search}%")
                            ->orWhere('candidates.election_year', 'like', "%{$search}%");
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('category', function($row){
                    return $row->category_name ?? '-';
                })
                ->addColumn('employee', function($row){
                    return $row->employee_name
                        ? $row->employee_name . ' [NID:' . ($row->nid ?? '-') . ']'
                        : '-';
                })
                ->addColumn('election_year', function($row){
                    return $row->election_year ?? '-';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at
                        ? \Carbon\Carbon::parse($row->created_at)
                            ->setTimezone('Asia/Dhaka')
                            ->diffForHumans()
                        : '';
                })
                ->addColumn('action', function($row){
                    $deleteUrl = route('candidate.delete', $row->id);
                    return '<a href="'. $deleteUrl .'" class="btn btn-sm btn-danger"><i class="fa fa-trash text-white"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.candidate.manage');
    }





    public function candidateCreate(){

        $employees = DB::table('employees')->get();
        $categories = DB::table('categories')->get();

        return view('backend.candidate.add' , compact('employees', 'categories'));
    }


    public function candidateUpload(Request $req) {
        // Validation
        $req->validate([
            'employee_id' => 'required|integer',
            'category_id' => 'required|integer',
            'year'        => 'required|integer',
        ], [
            'employee_id.required' => 'কর্মকর্তার আইডি দিতে হবে।',
            'category_id.required' => 'ক্যাটেগরি সিলেক্ট করুন।',
            'year.required'        => 'নির্বাচনের বছর দিতে হবে।',
        ]);

        // Duplicate check
        $exists = DB::table('candidates')
            ->where('employee_id', $req->employee_id)
            ->where('category_id', $req->category_id)
            ->where('election_year', $req->year)
            ->exists();

        if ($exists) {
            return back()->with('error', 'এই প্রার্থী ইতিমধ্যেই যোগ করা হয়েছে।')
                        ->withInput();
        }

        // Insert
        $status = DB::table('candidates')->insert([
            'employee_id' => $req->employee_id,
            'category_id' => $req->category_id,
            'election_year' => $req->year,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        if ($status) {
            return redirect()->route('candidate.manage')
                ->with('success', 'প্রার্থী সফলভাবে যোগ করা হয়েছে।');
        } else {
            return back()
                ->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')
                ->withInput();
        }
    }



    public function candidateDelete($id) {
        try {
            $deleted = DB::table('candidates')->where('id', $id)->delete();

            if ($deleted) {
                return redirect()->back()->with('success', 'প্রার্থী  সফলভাবে মুছে ফেলা হয়েছে।');
            } else {
                return redirect()->back()->with('error', 'প্রার্থী  খুঁজে পাওয়া যায়নি বা মুছে ফেলা যায়নি।');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।');
        }
    }

}

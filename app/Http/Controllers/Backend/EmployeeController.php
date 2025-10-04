<?php

namespace App\Http\Controllers\Backend;

use Exception;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function employeeManage(Request $req) {
        $query = DB::table('employees')
            ->join('districts', 'employees.district', '=', 'districts.id')
            ->join('designations', 'employees.designation', '=', 'designations.id')
            ->select('employees.id', 'employees.name', 'employees.nid','employees.email','employees.phone', 'districts.name as district_name','designations.name as designation_name', 'employees.created_at');

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
                    $editUrl = route('employee.edit', $row->id);
                    $deleteUrl = route('employee.delete', $row->id);

                    return '<a href="'. $editUrl .'" class="btn btn-sm btn-warning"><i class="fa fa-edit text-black"></i></a> 
                            <a href="'. $deleteUrl .'" class="btn btn-sm btn-danger" onclick="return confirm(\'আপনি কি নিশ্চিত মুছে ফেলতে চান?\')"><i class="fa fa-trash text-white"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("backend.employee.manage");
    }


    public function employeeCreate(Request $req){

        $districts = DB::table("districts")->get();
        $employees = DB::table('employees')->get();
        $categories = DB::table('categories')->get();
        $designations = DB::table('designations')->where('status', 1)->get();

        return view("backend.employee.add" , compact("districts", 'categories', 'employees', 'designations'));
    }


    public function employeeUpload(Request $request){
        
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nid'         => 'nullable|string|max:50',
            'email'       => 'required',
            'phone'       => 'required|string|max:20|unique:employees,phone',
            'password'    => 'nullable|string|min:6',
            'designation' => 'nullable|string|max:255',
            'district'    => 'nullable|string|max:255',
            'upazila'     => 'nullable|string|max:255',
            'working_place' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            $photoPath = null;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time().'_'.$file->getClientOriginalName();

                $file->move(public_path('uploads/employees'), $filename);

                $photoPath = 'uploads/employees/' . $filename;
            }

            $employee = Employee::create([
                'name'          => $validated['name'],
                'photo'         => $photoPath,
                'nid'           => $validated['nid'] ?? null,
                'email'         => $validated['email'],
                'phone'         => $validated['phone'],
                'designation'   => $validated['designation'] ?? null,
                'district'      => $validated['district'] ?? null,
                'upazila'       => $validated['upazila'] ?? null,
                'working_place' => $validated['working_place'] ?? null,
            ]);

            DB::commit();

            // return redirect()->route("employee.manage")->with('success', 'সফলভাবে যোগ করা হয়েছে');
            return redirect()->back()->with('success', 'সফলভাবে যোগ করা হয়েছে');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

    }



    public function employeeEdit($id){
        $districts = DB::table("districts")->get();
        $employees = DB::table('employees')->get();
        $categories = DB::table('categories')->get();
        $designations = DB::table('designations')->where('status', 1)->get();
        $employee = DB::table("employees")->where("id", $id)->first();

        return view("backend.employee.edit" , compact("employee", "districts", 'categories', 'employees', 'designations'));
    }


    public function employeeUpdate(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nid'         => 'nullable|string|max:50',
            'email'       => 'required',
            'phone'       => 'required|string|max:20|unique:employees,phone,' . $employee->id,
            'password'    => 'nullable|string|min:6',
            'designation' => 'nullable|string|max:255',
            'district'    => 'nullable|string|max:255',
            'upazila'     => 'nullable|string|max:255',
            'working_place' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            if ($request->hasFile('photo')) {
                if ($employee->photo && file_exists(public_path($employee->photo))) {
                    unlink(public_path($employee->photo));
                }

                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/employees'), $filename);
                $employee->photo = 'uploads/employees/' . $filename;
            }

            $employee->name = $validated['name'];
            $employee->nid = $validated['nid'] ?? null;
            $employee->email = $validated['email'];
            $employee->phone = $validated['phone'];
            if (!empty($validated['password'])) {
                $employee->password = Hash::make($validated['password']);
            }
            $employee->designation = $validated['designation'] ?? null;
            $employee->district = $validated['district'] ?? null;
            $employee->upazila = $validated['upazila'] ?? null;
            $employee->working_place = $validated['working_place'] ?? null;

            $employee->save();

            DB::commit();

            return redirect()->route("employee.manage")->with('success', 'সফলভাবে আপডেট করা হয়েছে');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function employeeDelete($id){

        $delete = DB::table("employees")->where("id", $id)->delete();

        if($delete){
            return redirect()->route("employee.manage")->with('success', 'কর্মস্থল সফলভাবে মুছে ফেলা হয়েছে');
        }else{
            return redirect()->back()->with('success', 'কর্মস্থল মুছে ফেলা যায়নি, আবার চেষ্টা করুন');
        }

    }

}

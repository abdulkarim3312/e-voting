<?php

namespace App\Http\Controllers\Userend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use DB;

class profileController extends Controller
{
    public function userProfile(){
        $districts = DB::table('districts')->get();
        $user = DB::table('employees')->where('id',session('employee_id'))->first();
        return view("userend.profile.view" , compact("user" , "districts"));

    }


    public function userUpdate(Request $req){
        
        $req->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:employees,email,' . session('employee_id'),
            'phone'      => 'required|string|max:20',
            'nid'        => 'required|string|max:30',
        ], [
            'name.required'  => 'নাম লিখতে হবে',
            'email.required' => 'ইমেইল দিতে হবে',
            'email.unique'   => 'এই ইমেইল ইতিমধ্যেই ব্যবহার হয়েছে',
            'photo.image'    => 'শুধুমাত্র ইমেজ ফাইল দিতে পারবেন',
            'photo.max'      => 'ইমেজ 2MB এর বেশি হতে পারবে না',
        ]);

        $user = DB::table('employees')
            ->where('id', session('employee_id'))
            ->first(['photo']);

            
        if ($req->hasFile('photo')) {
            if ($user->photo != null && file_exists($user->photo)) {
                unlink($user->photo);
            }
            $image      = $req->file('photo');
            $imageName  = time().'.'.$image->getClientOriginalExtension();
            $directory  = 'uploads/employee/';
            $imageUrl   = $directory.$imageName;
            $image->move($directory, $imageName);
        } else {
            $imageUrl = $user->photo;
        }

        
        $status = DB::table("employees")->where("id", session('employee_id'))
            ->update([
                "name"         => $req->name,
                "email"        => $req->email,
                "phone"        => $req->phone,
                "nid"          => $req->nid,
                "working_place"=> $req->officeLoc,
                "district"     => $req->district,
                "designation"  => $req->designation,
                "photo"        => $imageUrl,
                "updated_at"   => now(),
            ]);

        return redirect()->back()->with('success','Profile updated successfully');
    }



}

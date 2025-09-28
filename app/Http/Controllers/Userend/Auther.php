<?php

namespace App\Http\Controllers\Userend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Hash;
use Session;

class Auther extends Controller
{
    public function userCreate(Request $req) 
    { 

        $req->validate([
            'emp_name' => 'required|string|max:255',
            'emp_email' => 'required|email|unique:employees,email',
            'emp_password' => 'required|string|min:6',
        ], [
            'emp_email.unique' => 'এই ইমেইল ইতিমধ্যেই ব্যবহার করা হয়েছে!',
        ]);



       $action = DB::table('employees')->insert([
            'name' => $req->emp_name,
            'email' => $req->emp_email,
            'password' => Hash::make($req->emp_password),
        ]);


        if ($action) {
            $user_id =  DB::table('employees')->where('email', $req->emp_email)->value('id');
            session::put('employee_id',$user_id);

            return redirect('/user/dashboard');

        }else {
            
            return back()->with('error','কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।');
            
        }
    }



    public function userLogin(Request $req) {
        $req->validate([
            'emp_email' => 'required|email',
            'emp_password' => 'required|string|min:6',
        ]);

        $user = DB::table('employees')->where('email', $req->emp_email)->first();

        if (Hash::check($req->emp_password, $user->password)) {
            session(['employee_id' => $user->id]); 
            return redirect('/user/dashboard');
        } else {
            return back()->with('error', 'ইমেইল বা পাসওয়ার্ড ভুল হয়েছে। আবার চেষ্টা করুন।');
        }



    }




    public function userLogout(Request $req) {

        session()->flush();
        return redirect('/user/login')->with('success', 'সফলভাবে লগআউট হয়েছে। আবার লগইন করুন।');

    }









}

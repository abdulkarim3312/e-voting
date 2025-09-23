<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });
    }

    public function profilePage(){
        $user = User::where('id', $this->user->id)->first();
        return view('backend.profile.create', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|max:255',
        ]);

        $this->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        session()->flash('success', 'Profile updated successfully !!');
        return redirect()->back();
    }

    public function passwordPage(){
        return view('backend.profile.password');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required|min:8',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // At least one lowercase letter
                'regex:/[A-Z]/',      // At least one uppercase letter
                'regex:/[0-9]/',      // At least one digit
                'regex:/[@$!%*?&#]/', // At least one special character
                'confirmed',          // Ensures password_confirmation matches
            ],
        ]);
        
        // Check if the old password is correct
        if (!Hash::check($request->old_password, $this->user->password)) {
            throw ValidationException::withMessages([
                'old_password' => 'Old Password is incorrect.',
            ]);
        }
        
        // Update the password
        $this->user->update([
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully !!',
        ], 200);
    }
}

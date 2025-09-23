<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\GlobalSetting;
use App\Http\Controllers\Controller;

class GlobalSettingController extends Controller
{
    public function create()
    {
        $globalSettings = GlobalSetting::first();
        return view('backend.global_setting.create', compact('globalSettings'));
    }

    public function updateOrCreateGlobalSetting(Request $request)
    {
        $globalSettings = GlobalSetting::first();
        if($globalSettings){
            $globalSettings->update([
                'contact'       => $request->contact,
                'email'         => $request->email,
                'facebook'      => $request->facebook,
                'twitter'       => $request->twitter,
                'google_plus'   => $request->google_plus
            ]);
        }else{
            GlobalSetting::create([
                'contact'       => $request->contact,
                'email'         => $request->email,
                'facebook'      => $request->facebook,
                'twitter'       => $request->twitter,
                'google_plus'   => $request->google_plus
            ]);
        }
        session()->flash('success', 'Global settings updated successfully!');
        return redirect()->back();
    }
}

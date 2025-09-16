<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function general(){
           return view('backend.pages.setting.general');
    }
    public function information(){
           return view('backend.pages.setting.information');
    }
   

     public function store(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            if ($request->hasFile($key)) {
                $value = uploadFile($request->file($key));
            }

            $setting = Setting::firstOrNew(['name' => $key]);

            if ($setting->exists && $setting->value != $value) {
                if ($setting->value && file_exists(public_path($setting->value))) {
                    unlink(public_path($setting->value));
                }
            }

            $setting->value = $value;
            $setting->save();
        }

        session()->flash('success', 'Data updated successfully!');
        return back();
    }
}

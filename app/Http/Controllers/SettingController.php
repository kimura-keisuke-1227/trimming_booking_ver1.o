<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(){
        $settings = Setting::orderBy('order')
        ->get();
 
        return view('admin.setting.setting',[
            'settings' => $settings
        ]);
    }
}

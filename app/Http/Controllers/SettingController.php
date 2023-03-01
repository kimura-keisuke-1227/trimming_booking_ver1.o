<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\classes\Util;

class SettingController extends Controller
{
    public function index(){
        $settings = Setting::orderBy('order')
        ->get();

        session(['settingList' => $settings]);
 
        return view('admin.setting.setting',[
            'settings' => $settings
        ]);
    }

    public function update(){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return __METHOD__;
    }

}

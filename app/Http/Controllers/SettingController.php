<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\classes\Util;
use Illuminate\Support\Facades\Redis;

class SettingController extends Controller
{
    public function index()
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ') start by user(' . Util::getUserId() . ')');
        $settings = Setting::orderBy('order')
        ->get();
        
        session(['settingList' => $settings]);
        
        Log::info(__METHOD__ . '(' . __LINE__ . ') end by user(' . Util::getUserId() . ')');
        return view('admin.setting.setting', [
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ') start by user(' . Util::getUserId() . ')');
        
        $setting_list = session('settingList');

        foreach($setting_list as $setting){
            Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') setting:id=' . $setting->id . ' setting:setting_name:' . $setting->setting_name);
            $now_setting = Setting::find($setting->id);
            if($now_setting->isNumber==1){
                Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') This setting is numeric.');
            } else{
                Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') This setting is numeric.');

            }
        }
        
        Log::info(__METHOD__ . '(' . __LINE__ . ') end by user(' . Util::getUserId() . ')');
        return __METHOD__;
    }
}

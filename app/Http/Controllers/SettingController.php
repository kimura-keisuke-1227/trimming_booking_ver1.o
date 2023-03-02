<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\classes\Util;
use Exception;
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
        Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') $request:');
        Log::debug($request);
        $setting_list = session('settingList');

        foreach($setting_list as $setting){
            $modified = true;
            Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') setting:id=' . $setting->id . ' setting:setting_name:' . $setting->setting_name);
            $now_setting = Setting::find($setting->id);
            
            // 設定idから設定リクエスト内容を取得
            $set_value =  $request->get('setting-' . $setting->id);
            Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') set_value:' .$set_value);


            if($now_setting->isNumber==1){
                Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') This setting is numeric.');
                $modified = ($now_setting->setting_int<>$request->get('setting-' . $now_setting->id));
                Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') modified:' . $modified);
                $now_setting->setting_int = $request->get('setting-' . $now_setting->id);
            } else{
                Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') This setting is not numeric.');
                $modified = ($now_setting->setting_string<>$request->get('setting-' . $now_setting->id));
                Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') modified:' . $modified);
                $now_setting->setting_string = $request->get('setting-' . $now_setting->id);
            }


            //modifiedの場合のみ更新
            if($modified){
                try{
                    $now_setting->save();
                    Log::notice(__METHOD__ . ' staff:user_id(' .Util::getUserId() . ') saved setting-id:' . $now_setting->id .' setting name:' . $now_setting->setting_name) ;
                }catch(Exception $e){
                    Log::error($e);
                    return 'エラーが発生しました。お手数ですが直接店舗にお電話ください。';
                }    
            }
        }
        
        Log::info(__METHOD__ . '(' . __LINE__ . ') end by user(' . Util::getUserId() . ')');
        return redirect()->route('admin.setting')
        ->with("success", "設定を変更しました。");;
    }
}

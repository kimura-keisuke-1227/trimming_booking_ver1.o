<?php
namespace app\classes;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class Util
{
    public static function minuteToTime($timeNum){
        $hour = (string) floor($timeNum / 60);
        $minute = (string) $timeNum % 60;

        return $hour . '時' . $minute . '分';
    }

    public static function imeToMinute($hour,$minute){
        return $hour * 60 + $minute;
    }

    public static function addDays($baseDay, $addDays){
        
        $time = '+' . $addDays .'day';
        return date('Y-m-d',strtotime($time,strtotime($baseDay)));
    }
    
    public static function dateFormat($date){
        return $date('m') . '月' . $date('d') . '日';
    }
    
    public static function getSetting($default, $setting_name, $isInt)
    {

        $setting = Setting::where('setting_name',$setting_name) -> get();
        Log::debug(__FUNCTION__ . ' setting:' . $setting);

        $settingHere = $setting;
        if ($settingHere -> count() == 0) {
            return $default;
        }

        if ($isInt) {
            return $settingHere->first() ->setting_int;
        } else {
            return $settingHere->first() ->setting_string;
        }
    }
}
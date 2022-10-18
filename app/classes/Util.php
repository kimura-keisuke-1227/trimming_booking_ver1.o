<?php
namespace app\classes;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class Util
{
    public static function getYMDWFromDbDate($date){
        $dateStr = date('Y年m月d日',strtotime($date));
        $week = array( "日", "月", "火", "水", "木", "金", "土" );

        $dateStr = $dateStr . '('. $week[ date('w',strtotime($date))] . ')';
        return $dateStr;
    }

    public static function dbDateToStrDate($date){
        $week = array( "日", "月", "火", "水", "木", "金", "土" );
        $dateStr = date('m/d',strtotime($date)) .'('. $week[ date('w',strtotime($date))] . ')';

        return $dateStr;
    }
    public static function dbDateToStrDateForBirthday($date){
        $dateStr = date('Y年m月d日',strtotime($date));

        return $dateStr;
    }

    public static function minuteToTime($timeNum){
        $hour = (string) floor($timeNum / 60);
        $minute = (string) ($timeNum % 60);

        return $hour . '時' . $minute . '分';
    }

    public static function minuteNumToTime($time){
        $hour = (string) floor($time / 60);
        $minute = (string) $time % 60;

        return $hour . '時' . $minute . '分';
    }

    public static function timeToMinute($hour,$minute){
        return $hour * 60 + $minute;
    }

    public static function addDays($baseDay, $addDays){
        
        $time = '+' . $addDays .'day';
        return date('Y-m-d',strtotime($time,strtotime($baseDay)));
    }
    
    /*
    public static function dateFormat($date){
        Log::debug(__FUNCTION__ . ' $date(m):' );
        Log::debug(__FUNCTION__ . ' $date(d):' );
        return '';
        return str($date('m')) . '月' . str($date('d')) . '日';
    }
    */

    public static function dateFormat($date){
        $week = array( "日", "月", "火", "水", "木", "金", "土" );
        $dateStr = date('m/d',strtotime($date)) .'('. $week[ date('w',strtotime($date))] . ')';

        return $dateStr;
    }
    
    public static function getSetting($default, $setting_name, $isInt)
    {   
        $user = Auth::user();

        Log::debug(__METHOD__.'('.__LINE__.') start!');
        $setting = Setting::where('setting_name',$setting_name) -> get();

        if(is_null($user)){
            $reader = 'Non Member User';
        } else{
            $reader = $user->id;
        }

        Log::info(__FUNCTION__ .' user('. $reader . ') get setting '. $setting_name. ' (' . $setting .')');
        
        
        $settingHere = $setting;
        
        //設定がなかった場合
        Log::debug(__METHOD__.'('.__LINE__.') end ');
        if ($settingHere -> count() == 0) {
            return $default;
        }

        if ($isInt) {
            return $settingHere->first() ->setting_int;
        } else {
            return $settingHere->first() ->setting_string;
        }
    }

    public function getDaysList($st_date,$ed_date){
        $days = [];
        for ($i = $st_date; $i <= $ed_date; $i = $this -> addDays($i, 1)) {
            $days[$i] = $i;
        }

        return $days;
    }

    public function getTimes($st_time,$ed_time,$step_time){
        $times = [];
        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $str_time = Util::minuteToTime($time);
            $times[$time] = $str_time;
            Log::debug(__METHOD__.'('.__LINE__.')');
        }
        return $times;
    }
    public function getTimesNum($st_time,$ed_time,$step_time){
        $timesNum = [];
        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $str_time = Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }
        return $timesNum;
    }
    public static function getAge($date, $birthday){
        $birthdayYear = date('Y', strtotime($birthday));
        $birthdayMonth = date('m', strtotime($birthday));
        $birthdayDay = date('d', strtotime($birthday));

        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));

        $age =  $year - $birthdayYear-1;

        $birth_month_year = $birthdayMonth*100 + $birthdayDay;
        $month_year = $month*100 + $day;

        if($month_year >= $birth_month_year){
            $age  = $age+1;
        }

        return (string) $age . '歳';
    }

    public static function getEndOfTheMonth($date, $addMonth){

        
        return date("Y-m-t", strtotime($date. (string) $addMonth ."month"));
    }

    public function saveAccessLog($user_id,$method,$message){
        Log::debug(__METHOD__.'('.__LINE__.') start!');
        Log::debug(__METHOD__.'('.__LINE__.') end!');
    }
    
    public function closeBooked($salon_id, $date,$st_time,$ed_time,$course_id){
        Log::debug(__METHOD__.'('.__LINE__.') start!');
        
        Log::debug(__METHOD__.'('.__LINE__.') end!');
    }
}
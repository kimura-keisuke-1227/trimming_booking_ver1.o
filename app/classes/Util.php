<?php
namespace app\classes;
use Illuminate\Support\Facades\Log;

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
    
}
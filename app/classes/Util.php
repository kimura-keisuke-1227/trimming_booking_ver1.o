<?php

namespace app\classes;

use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use App\Models\User;
use App\Models\Booking;
use App\Models\Pet;
use App\Models\Salon;
use Illuminate\Support\Facades\Auth;
use App\Models\OpenCloseSalon;
use Illuminate\Support\Facades\DB;

use App\Models\Notification;

use App\Models\AccessLog;

class Util
{
    public static function getViewWithNotifications($view,$params){
        Log::info(__METHOD__.'('.__LINE__.') start!');
        $new_params = $params;
        $now = date('Y-m-d H:i');
        
        $notifications = Notification::query()
        // ->where('page',$view)
        ->where('st_date','<=',$now)
        ->where('ed_date','>=',$now)
        ->get();
        
        $new_params['messages'] = $notifications;
        Log::info(__METHOD__.'('.__LINE__.') end!');
        return view($view,$new_params);
    }

    public static function getNotifications($page){
        $now = date('Y-m-d H:i');
        Log::debug(__METHOD__.'('.__LINE__.') $now:' . $now);
        $notifications = Notification::query()
        // ->where('page',$page)
        ->where('st_date','<=',$now)
        ->where('ed_date','>=',$now)
        ->get();

        Log::debug(__METHOD__.'('.__LINE__.') notifications');
        Log::debug($notifications);
        return $notifications;
    }

    public static function recordAccessLog($method_name,$user_info,$summary,$detail,$sent_request){
        Log::info(__METHOD__.'('.__LINE__.')'.'start!');
        $user_id = Util::getUserId();
        Log::debug(__METHOD__.'('.__LINE__.')'.'user_id:' .$user_id.' $user(' . $user_info .') summary:' . $summary);
        Log::debug(__METHOD__.'('.__LINE__.')'.'detail:' . $detail);

        if(! $sent_request==""){
            $log_request = $sent_request;
        } else{
            $log_request = "";
        }

        $id = AccessLog::create([
            AccessLog::STR_COLUMN_NAME_OF_USER_ID => $user_id,
            AccessLog::STR_COLUMN_NAME_OF_METHOD => $method_name,
            AccessLog::STR_COLUMN_NAME_OF_USER_INFO => $user_info,
            AccessLog::STR_COLUMN_NAME_OF_SUMMARY => $summary,
            AccessLog::STR_COLUMN_NAME_OF_DETAIL => $detail,
            AccessLog::STR_COLUMN_NAME_OF_REQUEST => $log_request,
        ]);
        Log::info(__METHOD__.'('.__LINE__.')'.'end!');
        return $id;
    }

    /**
     * 日付データから、2024年1月28日（日）のような形式を返す
     *
     * @param [date] $date
     * @return string
     */
    public static function getYMDWFromDbDate($date)
    {
        $dateStr = date('Y年m月d日', strtotime($date));
        $week = array("日", "月", "火", "水", "木", "金", "土");

        $dateStr = $dateStr . '(' . $week[date('w', strtotime($date))] . ')';
        return $dateStr;
    }

    /**
     * 店舗の数を取得。複数店舗の場合に選択する場合に追加
     *
     * @return int
     */
    public static function getCountSalons(){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        $count_salons = Salon::count();
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return $count_salons;
    }

    /**
     * 日付から　1/28(日)のような形式を返す
     *
     * @param [date] $date
     * @return string
     */
    public static function dbDateToStrDate($date)
    {
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $dateStr = date('m/d', strtotime($date)) . '(' . $week[date('w', strtotime($date))] . ')';

        return $dateStr;
    }
    public static function dbDateToStrDateForBirthday($date)
    {
        $dateStr = date('Y年m月d日', strtotime($date));

        return $dateStr;
    }

    /**
     * 整数時間を時刻に変換し、●:●の形式で表示
     *
     * @param [int] $time
     * @return string
     */
    public static function minuteToTime($timeNum)
    {
        $hour = (string) floor($timeNum / 60);
        if($timeNum % 60==0){
            $minute = '00';
        } else{
            $minute = (string) ($timeNum % 60);
        }

        return $hour . ':' . $minute;
    }

    /**
     * 整数時間を時刻に変換し、●時●分の形式で表示
     *
     * @param [int] $time
     * @return string
     */
    public static function minuteNumToTime($time)
    {
        $hour = (string) floor($time / 60);
        $minute = (string) $time % 60;

        return $hour . '時' . $minute . '分';
    }

    /**
     * Undocumented function
     *
     * @param [int] $hour ●時
     * @param [int] $minute ●分
     * @return int
     */
    public static function timeToMinute($hour, $minute)
    {
        return $hour * 60 + $minute;
    }

    /**
     * もとに日付に整数で追加する日数を加算してN日後を日付で返す
     *
     * @param [date] $baseDay
     * @param [int] $addDays
     * @return date
     */
    public static function addDays($baseDay, $addDays)
    {
        Log::debug(__METHOD__.'('.__LINE__.')'.'baseDay:' . $baseDay .' $addDays:' . $addDays);
        $time =  $addDays . 'day';
        $result = date('Y-m-d', strtotime($time, strtotime($baseDay)));
        Log::debug(__METHOD__.'('.__LINE__.')'.'result:' . $result);
        return $result;
    }

    /*
    public static function dateFormat($date){
        Log::debug(__FUNCTION__ . ' $date(m):' );
        Log::debug(__FUNCTION__ . ' $date(d):' );
        return '';
        return str($date('m')) . '月' . str($date('d')) . '日';
    }
    */

    public static function dateFormat($date)
    {
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $dateStr = date('m/d', strtotime($date)) . '(' . $week[date('w', strtotime($date))] . ')';

        return $dateStr;
    }

    /**
     * 設定のDB値を返す
     *
     * @param [type] $default 設定がない場合のデフォルト
     * @param [type] $setting_name　設定名
     * @param [boolean] $isInt 整数型かどうか
     * @return int str 
     */
    public static function getSetting($default, $setting_name, $isInt)
    {
        $user = Auth::user();

        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');
        $setting = Setting::where('setting_name', $setting_name)->get();

        if (is_null($user)) {
            $reader = 'Non Member User';
        } else {
            $reader = $user->id;
        }

        Log::info(__FUNCTION__ . ' user(' . $reader . ') get setting ' . $setting_name . ' (' . $setting . ')');


        $settingHere = $setting;

        //設定がなかった場合
        Log::debug(__METHOD__ . '(' . __LINE__ . ') end ');
        if ($settingHere->count() == 0) {
            return $default;
        }

        if ($isInt) {
            return $settingHere->first()->setting_int;
        } else {
            return $settingHere->first()->setting_string;
        }
    }

    /**
     * 開始日と終了日を指定して、その間の日付のリストを返す
     *
     * @param [type] $st_date 開始日
     * @param [type] $ed_date 終了日
     * @return list
     */
    public function getDaysList($st_date, $ed_date)
    {
        $days = [];
        for ($i = $st_date; $i <= $ed_date; $i = $this->addDays($i, 1)) {
            $days[$i] = $i;
        }

        return $days;
    }

    /**
     * 整数時間のリストを返す。
     *
     * @param [type] $st_time 開店時間
     * @param [type] $ed_time 終了時間
     * @param [type] $step_time ○分ごとの設定
     * @return list
     */
    public function getTimes($st_time, $ed_time, $step_time)
    {
        $times = [];
        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $str_time = Util::minuteToTime($time);
            $times[$time] = $str_time;
            Log::debug(__METHOD__ . '(' . __LINE__ . ')');
        }
        return $times;
    }

    /**
     * 予約の時間を何枠用意するか 30分枠で設定して8時間なら１６枠
     *
     * @param [type] $st_time
     * @param [type] $ed_time
     * @param [type] $step_time
     * @return int
     */
    public function getTimesCount($st_time, $ed_time, $step_time)
    {   Log::debug(__METHOD__.'('.__LINE__.') starts!');
        $timesCount = ($ed_time - $st_time)/ $step_time;
        // 切り上げる
        $timesCount = ceil($timesCount);

        Log::debug(__METHOD__.'('.__LINE__.') st_time:' . $st_time . ' ed_time:' . $ed_time . ' step_time:' . $step_time . ' $timesCount'. $timesCount);
        Log::debug(__METHOD__.'('.__LINE__.') ends!');
        return $timesCount;
    }

    public function getTimesForSP($st_time, $ed_time, $step_time){
        $times = [];
        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $hour = (string) floor($time / 60);
            $minute = (string) ($time % 60);
            $str_time = $hour .':'. $minute;
            $times[$time] = $str_time;
            Log::debug(__METHOD__ . '(' . __LINE__ . ')');
        }
        return $times;
    }
    public function getTimesNum($st_time, $ed_time, $step_time)
    {
        $timesNum = [];
        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $str_time = Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }
        return $timesNum;
    }
    /**
     * 日付と生年月日から年齢を計算
     *
     * @param [type] $date
     * @param [type] $birthday
     * @return string
     */
    public static function getAge($date, $birthday)
    {
        $birthdayYear = date('Y', strtotime($birthday));
        $birthdayMonth = date('m', strtotime($birthday));
        $birthdayDay = date('d', strtotime($birthday));

        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));

        $age =  $year - $birthdayYear - 1;

        $birth_month_year = $birthdayMonth * 100 + $birthdayDay;
        $month_year = $month * 100 + $day;

        if ($month_year >= $birth_month_year) {
            $age  = $age + 1;
        }

        return (string) $age . '歳';
    }

    /**
     * 月末のdayを取得
     *
     * @param [type] $date
     * @param [type] $addMonth
     * @return void
     */
    public static function getEndOfTheMonth($date, $addMonth)
    {


        return date("Y-m-t", strtotime($date . (string) $addMonth . "month"));
    }

    public function saveAccessLog($user_id, $method, $message)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');
        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');
    }

    public function closeBooked($salon_id, $date, $st_time, $ed_time, $course_id,$access_log_id)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');

        $step_time = $this->getSetting(30, 'step_time', true);
        // 操作記録をDBに
        $user =Auth::user();
        $realIp = request()->ip();
        $user_info = "user_id({$user->id}) IP[{$realIp}]";

        $insertsDatas = [];
        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $openCloseSalon = [
                'salon_id' => $salon_id,
                'course_id' => $course_id,
                'date' => $date,
                'isOpen' => 0,
                'access_log_id' => $access_log_id->id,
                'user_id' => $user->id,
                'user_info' => $user_info,
                'time' => $time,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $insertsDatas[] = $openCloseSalon;
        }

        DB::table('open_close_salons')->insert($insertsDatas);
        Log::debug(__METHOD__ . '(' . __LINE__ . ') inserted datas!');
        Log::debug($insertsDatas);


        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');
    }


    /**
     * 過去に来店歴がある人をリストで返す
     *
     * @param [type] $date
     * @param [type] $salon_id
     * @return list
     */
    public static function getWhoCameBefore($date,$salon_id)
    {
        $usersCameBeforeList = [];


        $todays_pets_id = Booking::query()
        ->where('date', '=', $date)
        ->where('salon_id', '=', $salon_id)
        ->get('pet_id');


        Log::debug(__METHOD__.'('.__LINE__.')'.'todays_pets:');
        Log::debug($todays_pets_id);

        $todays_owners = Pet::query()
        ->whereIn('id',$todays_pets_id)
        ->get('owner_id');
        Log::debug(__METHOD__.'('.__LINE__.')'.'todays_owners:');
        Log::debug($todays_owners);



        // return $usersCameBeforeList;
        $users = User::query()
        ->whereIn('id',$todays_owners)
        ->get()
        ;
        $pets = Pet::query()
        ->whereIn('owner_id',$todays_owners)
        ;
        $staff = Auth::user();
        Log::debug(__METHOD__.'('.__LINE__.') sraff(' . $staff->id .') got all Users and Pets info!' );
        Log::debug(__METHOD__.'('.__LINE__.') sraff(' . $staff->id .') is getting all Bookings info before ' . $date);
        $allBookings = Booking::where('date', '<', $date)
        ->whereIn('pet_id',$todays_pets_id)
        ->get();
        Log::debug(__METHOD__.'('.__LINE__.') sraff(' . $staff->id .') got all Bookings info before ' . $date);

        foreach($users as $user){
            $count = $user->cameBefore;
            $pets_of_user = $pets->where('owner_id',$user->id);
            foreach($pets_of_user as $pet_of_user){
                $count = $count + $allBookings->where('pet_id',$pet_of_user->id) -> count();

                Log::debug(__METHOD__.'('.__LINE__.') user('. $user->id .') pet_id(' .$pet_of_user->id.') count:'.$count);
            }

            #Log::debug(__METHOD__.'('.__LINE__.') user('. $user->id .') pets_of_user:' . $pets_of_user);
            #$bookings_of_user = $allBookings->wherein('pet_id',$pets_of_user->id);
            #Log::debug(__METHOD__.'('.__LINE__.') user(' . $user->id .') booking:');
            $usersCameBeforeList[$user->id] = $count;
        }
        Log::debug(__METHOD__.'('.__LINE__.') usersCameBeforeList:');
        Log::debug($usersCameBeforeList);

        return $usersCameBeforeList;
    }

    public static function getUserId(){
        $user =Auth::user();
        if(is_null($user)){
            return 'not login user';
        } else{
            return $user->id;
        }
    }

    /**
     * このユーザーが過去に来店歴が有るかどうかをチェック
     *
     * @param [type] $user_id　ユーザーID
     * @param [type] $date 調べる日付　日付によっても結果は異なるはず。
     * @return void
     */
    public function getTheUserCameBefore($user_id,$date){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . $this->getUserId() .') with $user_id:' .$user_id . ' date:' . $date);
        $user = User::find($user_id);

        if(is_null($user)){
            Log::info(__METHOD__.'('.__LINE__.') the user is null.');
            Log::info(__METHOD__.'('.__LINE__.') end by user(' . $this->getUserId() .')');
            return false;
        }

        $theUserCameBefore = false;

        //ユーザーが来店ありと申告した場合はそのまま。　DBをこれ以上読む必要もないため早めにreturn
        if($user->cameBefore == 1){
            Log::debug(__METHOD__.'('.__LINE__.') the user came before when (s)he registered.');
            $theUserCameBefore = true;
            Log::info(__METHOD__.'('.__LINE__.') end by user(' . $this->getUserId() .')');
            return $theUserCameBefore;
        }
        
        //登録時点で来店がない場合には過去の予約を調べる
        //予約データは飼い主ではなくペットに紐付いているため、まずはユーザーのペットを取得する。
        $pets_id = Pet::select('id')->where('owner_id',$user_id)->get();
        
        //判定日よりも前に来店があるかどうかを数える。
        $cameBeforeCount = Booking::where('date','<',$date)
        ->whereIn('pet_id',$pets_id)
        ->count();
        
        Log::debug(__METHOD__.'('.__LINE__.') $cameBeforeCount='.$cameBeforeCount) ;
        
        if($cameBeforeCount == 0){
            $theUserCameBefore = false;
        } else{
            $theUserCameBefore = true;
        }

        Log::info(__METHOD__.'('.__LINE__.') end by user(' . $this->getUserId() .')');
        return $theUserCameBefore;
    }

    /**
     * 整数分を4桁の時間に変更
     * (例)10：00を表す　600を 1000に変更
     *
     * @param [type] $intTime
     * @return int
     */
    public static function get4digitTime($intTime){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        $hour = floor($intTime/60);
        $min = $intTime%60;
        Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') $intTime='.$intTime.' $hour='. $hour . ' $min=' .$min);
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return $hour*100+$min;

    }
}

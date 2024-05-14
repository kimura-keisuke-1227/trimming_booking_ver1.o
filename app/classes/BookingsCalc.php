<?php

namespace app\classes;

use App\Models\Salon;
use Illuminate\Support\Facades\Log;
use App\classes\Util;
use App\Models\TempCapacity;
use App\Models\RegularHoliday;
use App\Http\Controllers\OpenCloseSalonController;
use Illuminate\Support\Facades\Redis;

class BookingsCalc
{
    public static function getAllBookingsByDate($bookings, $date)
    {
        Log::debug(__METHOD__ . '(start)');
        $start = microtime(true);
        $dateBookings = $bookings
            ->where('date', $date)
            ->sortBy('st_time');
        $end = microtime(true);
        Log::debug(__METHOD__ . ' ' . $end - $start . '(s)');
        Log::debug(__METHOD__ . '(end)');
        return $dateBookings;
    }

    public function getCanBookList2($salon_id, $course_master_id, $st_date, $ed_date, $step_time, $st_time, $ed_time, $needed_time)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') starts.');
        $openCloseSalonController = new OpenCloseSalonController();

        $openCloseSalonController = new OpenCloseSalonController();
        Log::debug(__METHOD__ . '(' . __LINE__ . ') course_master_id: ' . $course_master_id);

        $openCloseList =
            $openCloseSalonController->makeOpenCloseListFromStdateToEddate($salon_id, $course_master_id, $st_date, $ed_date, $st_time, $ed_time, $step_time);

        #Log::debug(__METHOD__ . '(' . __LINE__ . ')  $openCloseList with salon_id:' . $salon_id . ' course_master_id:' .$course_master_id . ' st_date:' . $st_date .' ed_date:'.$ed_date );
        #Log::debug($openCloseList);
        $canBookList = [];
        for($day = $st_date; $day<=$ed_date;$day = Util::addDays($day,1)){
            for($time = $st_time; $time<$ed_time;$time=$time+$step_time){
                for($cutting_time = $time; $cutting_time< $time+ $needed_time; $cutting_time = $cutting_time + $step_time){
                    if($openCloseList[$day][$cutting_time] == 0){
                        Log::debug($day . ' ' . $time . ' closed!');
                        continue;
                    }
                    Log::debug('  $cutting_time:' . $cutting_time );
                }
                Log::debug(' $day:' . $day . ' $checkTime:' . $time);
                
            }
        }
        Log::debug(__METHOD__ . '(' . __LINE__ . ') day:' .$day );

        /*
        $canBookTable=[];
        for($date = $st_date; $date<=$ed_date;$date = Util::addDays($date,1)){
            $canBookTheDate = [];
            for($time =$st_time;$time<$ed_time;$time = $time + $step_time){
                $count = 1;
                for($checkTime = $time;$checkTime<$time+$needed_time;$checkTime =$checkTime + $step_time){
                    if(true){
                        $canBookTheDate = [$time] = 0;
                        continue;
                    }
                }
                $canBookTable[$date] = $canBookTheDate;
            }
        }
        */
        Log::debug(__METHOD__ . '(' . __LINE__ . ')  $canBookTable');
        #Log::debug($canBookTable);
        Log::debug(__METHOD__ . '(' . __LINE__ . ') ends.');
    }

    public function getCanBookList($allBookings, $allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date, $course)
    {
        Log::debug(__METHOD__ . '(start)');
        $start = microtime(true);

        Log::debug('$course:' . $course->id . ' minute:' . $course->minute);
        $getOtherCapacitiesOfMultiDate
            =  $this->getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date);
        $cut_time = $course->minute;
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;

        $canBookTimeListOfMultiDay = [];
        for ($date = $st_date; $date <= $ed_date; $date = Util::addDays($date, 1)) {
            $isTimeOver = false;

            //当日以前なら予約できない
            if ($date <= date('Y-m-d')) {
                $isTimeOver = true;
            }


            //前日の閉店時刻を過ぎていたら予約できない。
            $nowTime = date('H') * 60 + date('i');
            Log::debug(__FUNCTION__ . '$nowTime:' . $nowTime);

            $deadTimeForBooking = Util::getSetting(30, 'deadTimeForBooking', true);
            if (($date == Util::addDays(date('Y-m-d'), 1)) and ($nowTime + $deadTimeForBooking > $ed_time)) {
                $isTimeOver = true;
            }

            if ($isTimeOver) {
                for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
                    $canBookTimeListOfADay[$time] = 0;
                }
                $canBookTimeListOfMultiDay[$date] = $canBookTimeListOfADay;
                continue;
            }

            $untilTheDayAhead = Util::getSetting(30, 'untilTheDayAhead', true);

            if ($date >= Util::addDays(date('Y-m-d'), $untilTheDayAhead)) {
                for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
                    $canBookTimeListOfADay[$time] = 0;
                }
                $canBookTimeListOfMultiDay[$date] = $canBookTimeListOfADay;
                continue;
            }

            $getOtherCapacitiesOfADate
                = $getOtherCapacitiesOfMultiDate[$date];

            $canBookTimeListOfADay = [];
            //20221012 現在時間+カット時間が閉店時刻よりも前の場合に表示
            for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {

                //カットの終了時間が閉店時間を超えたら枠0扱い
                $cutting_ed_time = $time + $cut_time;
                if ($cutting_ed_time > $ed_time) {
                    //Log::debug('閉店時間をすぎる');
                    $min_count  = 0;
                } else {
                    //Log::debug('閉店時間をすぎない');
                    $min_count = $getOtherCapacitiesOfADate[$time];
                    for ($cutting_time = $time; $cutting_time < $cutting_ed_time; $cutting_time = $cutting_time + $step_time) {
                        $temp_count = $getOtherCapacitiesOfADate[$cutting_time];
                        if ($temp_count < $min_count) {
                            $min_count = $temp_count;
                        }
                    }
                }
                $canBookTimeListOfADay[$time] = $min_count;
            }

            $canBookTimeListOfMultiDay[$date] = $canBookTimeListOfADay;
        }
        $end = microtime(true);
        Log::debug(__METHOD__ . ' ' . $end - $start . '(s)');
        Log::debug(__METHOD__ . '(end)');

        return $canBookTimeListOfMultiDay;
    }

    public function getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date)
    {
        Log::debug(__METHOD__ . '(start)');
        $start = microtime(true);
        $getOtherCapacitiesOfMultiDate = [];

        for ($date = $st_date; $date <= $ed_date; $date = Util::addDays($date, 1)) {
            $dateBookingCount
                =  $this->getBookingCountsOfMultiDaysFromStartDateToEndDate($allBookings, $salon, $step_time, $st_date, $ed_date)[$date];

            $dateCapacity
                = $this->getCapacitiesOfMultiDays($allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $st_date, $ed_date, $salon, $step_time)[$date];

            $otherCapacitiesOfTheDate
                =  $this->getOtherCapacitiesOfTheDate($dateBookingCount, $dateCapacity, $salon, $step_time);

            $getOtherCapacitiesOfMultiDate[$date] = $otherCapacitiesOfTheDate;
        }
        $end = microtime(true);
        Log::debug(__METHOD__ . ' ' . $end - $start . '(s)');
        Log::debug(__METHOD__ . '(end)');

        return $getOtherCapacitiesOfMultiDate;
    }

    private function getBookingCountsOfMultiDaysFromStartDateToEndDate($bookings, $salon, $step_time, $st_date, $ed_date)
    {
        Log::debug(__METHOD__ . '(start)');
        $start = microtime(true);
        $bookingCountsOfMultiDaysFromStartDateToEndDate = [];

        for ($day = $st_date; $day <= $ed_date; $day = Util::addDays($day, 1)) {
            $countsForADay =  $this->getTimeTableFromSalonDay($bookings, $salon, $day, $step_time);
            $bookingCountsOfMultiDaysFromStartDateToEndDate[$day] = $countsForADay;
        }
        Log::debug(__METHOD__ . '(end)');
        $end = microtime(true);
        Log::debug(__METHOD__ . ' ' . $end - $start . '(s)');
        return $bookingCountsOfMultiDaysFromStartDateToEndDate;
    }

    private function getCapacitiesOfMultiDays($allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $st_date, $ed_date, $salon, $step_time)
    {
        Log::debug(__METHOD__ . '(start)');
        $start = microtime(true);
        $capacitiesFromMultiDays = [];
        for ($date = $st_date; $date <= $ed_date; $date = Util::addDays($date, 1)) {
            $capacitiesFromMultiDays[$date] =
                $this->getCapacityFromDay($allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $date, $salon, $step_time);
        }

        $end = microtime(true);
        Log::debug(__METHOD__ . ' ' . $end - $start . '(s)');
        Log::debug(__METHOD__ . '(end)');
        return $capacitiesFromMultiDays;
    }


    private static function getOtherCapacitiesOfTheDate($dateBookingsCount, $dateCapacitiesCount, $salon, $step_time)
    {
        Log::debug(__METHOD__ . '(start)');
        $start = microtime(true);
        $otherCapacitiesOfTheDate = [];
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;

        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $bookingCount = $dateBookingsCount[$time];
            $capacity     = $dateCapacitiesCount[$time];

            $otherCapacitiesOfTheDate[$time] = $capacity - $bookingCount;
        }

        $end = microtime(true);
        Log::debug(__METHOD__ . ' ' . $end - $start . '(s)');
        Log::debug(__METHOD__ . '(end)');

        return $otherCapacitiesOfTheDate;
    }

    private function getTimeTableFromSalonDay($bookings, $salon, $date, $step_time)
    {
        Log::debug(__METHOD__ . '(start)');
        $start = microtime(true);
        $counts = [];

        //サロン情報を格納
        $salon_id = $salon->id;
        $open_time = $salon->st_time;
        $close_time = $salon->ed_time;

        $time = $open_time;

        while ($time < $close_time) {
            $count = $this->getBookingCountFromSalonDayTimeMinute($bookings, $salon_id, $date, $time);
            $counts[$time] = $count;

            $time = $time + $step_time;
        }
        Log::debug(__METHOD__ . '(end)');

        $end = microtime(true);
        Log::debug(__METHOD__ . ' ' . $end - $start . '(s)');
        return $counts;
    }

    //店舗の1日の枠数を取得
    private function getCapacityFromDay($allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $date, $salon, $step_time)
    {
        Log::debug(__METHOD__ . '(start)');
        $start = microtime(true);
        $open_time = $salon->st_time;
        $close_time = $salon->ed_time;
        $capacitiesOfTheDay = [];

        //指定日範囲内の臨時調整枠データを取得
        $tempCapacitiesOfTheDate = $allTempCapacities
            ->where('salon_id', '=', $salon->id)
            ->where('st_date', '<=', $date)
            ->where('ed_date', '>=', $date);

        //定休日の設定を取得
        $regularHolidaysOfTheSalon = $allRegularHolidays->where('salon_id', $salon->id);
        //Log::debug(__FUNCTION__ . ' 定休日:' . $regularHolidaysOfTheSalon);

        //DBから店舗のデフォルト受け入れ枠データ
        //開店時間から閉店時間まで
        for ($time = $open_time; $time < $close_time; $time = $time + $step_time) {

            //通常の受け入れ枠を設定
            $capacitiesOfTheDay[$time]
                =  $this->getDefaultCapacityOfTheDayAndSalon($allDefaultCapacities, $date, $salon);

            foreach ($regularHolidaysOfTheSalon as $regularHolidayOfTheSalon) {
                //定休日であればゼロに
                if (date('w', strtotime($date)) == $regularHolidayOfTheSalon->dayOfWeek) {
                    $capacitiesOfTheDay[$time]  = 0;
                }
            }

            //臨時枠調整があれば上書き
            foreach ($tempCapacitiesOfTheDate as $capacity) {
                #Log::debug('$capacity:' . $capacity);
                $st_time = $capacity->st_time;
                $ed_time = $capacity->ed_time;

                #Log::debug('$time:' . $time.' $st_time:' . $st_time .' $ed_time:' . $ed_time);
                $reWrite = true;

                if ($st_time > $time) {
                    $reWrite = false;
                }

                if ($ed_time < $time) {
                    $reWrite = false;
                }

                if ($reWrite) {
                    Log::debug(__FUNCTION__ . ' $time:' . $time . ' $st_time:' . $st_time . ' $ed_time:' . $ed_time . ' Rewirte:');
                    $capacitiesOfTheDay[$time] = $capacity->capacity;
                } else {
                    Log::debug(__FUNCTION__ . '$time:' . $time . ' $st_time:' . $st_time . ' $ed_time:' . $ed_time . ' No Rewirte:');
                }
            }
        }
        $end = microtime(true);
        Log::debug(__METHOD__ . ' ' . $end - $start . '(s)');
        Log::debug(__METHOD__ . '(end)');
        return $capacitiesOfTheDay;
    }

    //$cutTimeは、終了時間を算出するために必要
    public static function getBookingCountFromSalonDayTimeMinute($bookings, $salon_id, $date, $time)
    {
        Log::debug(__METHOD__ . '(start)');
        $start = microtime(true);
        //DBへの繰り返しアクセスを防ぐため、予約データを呼び出し時に受け取るようにする。

        $count =
            $bookings
            ->where('date', $date)
            ->where('salon_id', $salon_id)
            ->where('st_time', '<=', $time)
            ->where('ed_time', '>', $time)
            ->count();
        $end = microtime(true);
        Log::debug(__METHOD__ . ' ' . $end - $start . '(s)');
        Log::debug(__METHOD__ . '(end)');

        return $count;
    }

    private function getDefaultCapacityOfTheDayAndSalon($allDefaultCapacities, $date, $salon)
    {
        Log::debug(__METHOD__ . '(start)');
        $start = microtime(true);
        //DBへの繰り返しアクセスを防ぐため、デフォルトデータを呼び出し時に受け取るようにする。
        $salon_id = $salon->id;
        $dayOfWeekFromDate = date('w', strtotime($date));

        #Log::debug(__METHOD__ . '(' . __LINE__ . ')' . ' date:' . $date . ' dayOfWeek:' . $dayOfWeekFromDate);

        $capacity =
            $allDefaultCapacities
            ->where('salon_id', $salon_id)
            ->where('st_date', '<=', $date)
            ->where('dayOfWeek', $dayOfWeekFromDate)
            ->sortByDesc('st_date')
            ->first();

        $end = microtime(true);
        Log::debug(__METHOD__ . ' ' . $end - $start . '(s)');
        Log::debug(__METHOD__ . '(end)');
        return $capacity->capacity;
    }

    /**************************************************************
     *
     *　スタッフが○×を切り替えれるように
     *
     **************************************************************/

    public function getCapacitiesOfMultiDaysForOX($salonId, $st_date, $ed_date, $step_time)
    {
        Log::debug(__METHOD__ . '(starts)');
        Log::debug(__METHOD__ . ' get Setting TempCapacities by salon_id.');
        $allTempCapacities = TempCapacity::where('salon_id', $salonId)
            ->get();

        Log::debug('allTempCapacities:');
        #Log::debug($allTempCapacities);
        $salon = Salon::find($salonId)->first();

        Log::debug(__METHOD__ . ' get Setting Step time.');



        $acceptableCountsForMultiDays = [];
        for ($date = $st_date; $date <= $ed_date; $date = Util::addDays($date, 1)) {
            $acceptableCountsForMultiDays[$date] =
                $this->getTempCapacityOfTheDay($salon, $date, $step_time, $allTempCapacities);
            Log::debug($date);
        }

        #Log::debug($acceptableCountsForMultiDays);
        Log::debug(__METHOD__ . '(ends)');

        return $acceptableCountsForMultiDays;
    }

    private function getTempCapacityOfTheDay($salon, $date, $step_time, $allTempCapacities)
    {
        Log::debug(__METHOD__ . '(start)');
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;

        $dateTempCapacities = $allTempCapacities
            ->where('st_date', $date)
            ->where('salon_id', $salon->id);

        Log::debug(__METHOD__ . 'dateTempCapacities:');
        Log::debug($dateTempCapacities);
        #Log::debug(__METHOD__ . ' salon:' .  $salon);
        #Log::debug(__METHOD__ . ' st_time:' . (string)$st_time.' ed_time:'.(string)$ed_time);

        //とりあえず、設定がない場合は空き枠とする
        $acceptableCounts = [];
        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $acceptableCounts[$time] = 1;
        }

        foreach ($dateTempCapacities as $dateTempCapacity) {
            Log::debug(__METHOD__ . ' dateTempCapacity:');
            Log::debug($dateTempCapacity);
            Log::debug(' st_time:' . $dateTempCapacity->st_date . ' ed_time:' . $dateTempCapacity->st_time);
            $acceptableCounts[$dateTempCapacity->st_time] = $dateTempCapacity->capacity;
        }

        #Log::debug( $acceptableCounts);
        Log::debug(__METHOD__ . '(end)');

        return $acceptableCounts;
    }


    public function getCanBookList3($salon,$st_date,$ed_date,$needed_minutes,$course){
        Log::info(__METHOD__.'('.__LINE__.')'.'start!');
        Log::debug(__METHOD__.'('.__LINE__.')'.'$salon_id:' . $salon->id);

        // 開始日のチェック
        $today = date('Y-m-d');
        if($st_date<$today){
            Log::info(__METHOD__ . '(' . __LINE__ . ')' . '$st_date:' . $st_date . 'is past, changed into today:' . $today );
            $st_date = $today;
        }

        $openCloseSalonController = new OpenCloseSalonController();
        Log::debug(__METHOD__ . '(' . __LINE__ . ') course_master_id: ' );
        $course_master_id =  $course->course_master_id;
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;

        if($course_master_id==1){
            $booking_ed_time = env("FINAL_BOOKING_TIME_COURSE1",17)*60+1;
        }elseif($course_master_id==2){
            $booking_ed_time = env("FINAL_BOOKING_TIME_COURSE2",16)*60+1;
        }

        $step_time = Util::getSetting(30, 'step_time', true);

        $openCloseList =
            $openCloseSalonController->makeOpenCloseListFromStdateToEddate($salon->id,$course_master_id, $st_date, $ed_date, $st_time, $salon->ed_time, $step_time);

        // Log::debug(__METHOD__.'('.__LINE__.')'.'openCloseList');
        // Log::debug($openCloseList);

        $regular_holidais_of_the_salon = RegularHoliday::query()
            ->where('salon_id',$salon->id)
            ->get();

        Log::debug(__METHOD__.'('.__LINE__.')'.'regular_holidais_of_the_salon');
        Log::debug($regular_holidais_of_the_salon);

        // 定休日の曜日を格納する配列
        $list_of_holiday = [];
        foreach($regular_holidais_of_the_salon as $holiday){
            array_push($list_of_holiday,$holiday["dayOfWeek"] );
        }
        

        Log::debug(__METHOD__.'('.__LINE__.')'.'list_of_holiday');
        Log::debug($list_of_holiday);
        // 予約の可否を格納する配列初期値
        $capacities = [];


        // 確認日の最初から最後までのループ
        for($date=$st_date;$date<=$ed_date;$date = Util::addDays($date, 1)){
            $today_capacity =[];
            $today_openclose = $openCloseList[$date];
            Log::debug(__METHOD__.'('.__LINE__.')'.'today_openclose');
            Log::debug($today_openclose);

            $day_of_week = date("w",strtotime($date));
            Log::debug(__METHOD__.'('.__LINE__.')'.'$date:' . $date . ' day_of_week:' . $day_of_week);

            // 開店時間から予約受付終了時間までのループ
            for($time=$st_time;$time<=$booking_ed_time;$time=$time+$step_time){
                Log::debug(__METHOD__.'('.__LINE__.')'.$date .' ' . $time);

                // 定休日なら-1を代入して次へ
                if(in_array($day_of_week, $list_of_holiday)){
                    Log::debug(__METHOD__.'('.__LINE__.')'.'holiday:' . $date);
                    $today_capacity[$time] = -1;
                    continue;
                }

                //過ぎた日付なら×
                $today = date("Y-m-d");
                Log::debug(__METHOD__.'('.__LINE__.')'.'H:' .date('H'));
                if($date<$today){
                    $today_capacity[$time] = 0;
                    Log::debug(__METHOD__.'('.__LINE__.')'.'The date is past!');
                    continue;
                }

                // 当日でも、すでに過ぎた時間なら×
                $over_time = Util::timeToMinute(date("H"), date("i")) + env("TIME_TO_BOOK_BEFORE_X_MINUTE",60);
 
                Log::debug(__METHOD__.'('.__LINE__.') $time:' . $time.' $over_time:' . Util::minuteToTime($over_time));
                if($date==$today && $time<$over_time){
                    $today_capacity[$time] = 0;
                    Log::debug(__METHOD__.'('.__LINE__.')'.'The_time_is_to_late!');
                    continue;
                }

                // カット終了時間が閉店時間を超えていたら×で次へ
                Log::debug(__METHOD__.'('.__LINE__.')'.'close_time:' . $ed_time .' now_time:' .$time . ' needed_time:'. $needed_minutes);
                if($time+$needed_minutes>$ed_time){
                    $today_capacity[$time] = 0;
                    Log::debug(__METHOD__.'('.__LINE__.')'.'time is over!');
                    continue;
                }
                Log::info(__METHOD__.'('.__LINE__.')'.'start_to_check_time! time:' . $time);
                
                $temp_capacity = 1;

                for($checkTime=$time;$checkTime<$time+$needed_minutes;$checkTime=$checkTime+$step_time){
                    Log::debug(__METHOD__.'('.__LINE__.')'.'checktime:' . $checkTime);
                    $capacity_of_check = $today_openclose[$checkTime];
                    Log::debug(__METHOD__.'('.__LINE__.')'.$date .' ' . $checkTime . ' ' .$capacity_of_check);
                    if($capacity_of_check==0){
                        $temp_capacity=0;
                        Log::debug(__METHOD__.'('.__LINE__.')'.'break');
                        break;
                    }
                }
                
                $today_capacity[$time] = $temp_capacity;
            }
            Log::debug(__METHOD__.'('.__LINE__.')'.'today_capacity');
            Log::debug($today_capacity);
            $capacities[$date] =  $today_capacity;
        }
        Log::info(__METHOD__.'('.__LINE__.')'.'end!');
        Log::debug($capacities);
        return $capacities;
    }
}

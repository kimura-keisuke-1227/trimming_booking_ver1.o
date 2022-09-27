<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Salon;
use App\Models\DefaultCapacity;
use App\Models\TempCapacity;
use App\Models\USer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\classes\BookingsCalc;
use App\classes\Util;
use App\Http\Controllers\BookingController as ControllersBookingController;
use App\Models\CourseMaster;
use Symfony\Component\ErrorHandler\Debug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAdminMail;
use function Psy\debug;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function deleteConfirm($bookingID){
        $owner = Auth::user();
        $booking = Booking::find($bookingID);

        $owner_id = $owner -> id;
        if(! is_null($booking)){
            $booking_owner_id = $booking -> pet -> user -> id;
            
        }

        if((is_null($booking) or ($owner_id != $booking_owner_id))){
            return redirect('/bookings')
            -> with('error' , '該当の予約が存在しません。');
        }


        return view('bookings.cancelConfirm',[
            'booking' => $booking
        ]);
    }

    public function selectBookingCalender(Request $request, $date, $time){
        $owner = Auth::user();
        $pet =  session('pet');
        $course =  session('course');
        $date = $request -> date;
        $time = $request -> time;
        $timeStr = Util::minuteToTime($time);
        session([
            'date' => $date,
            'time' => $time,
        ]);
        return view('bookings.confirm',[
            'owner' => $owner,
            'pet' => $pet,
            'course' => $course,
            'date' => $date,
            'time' => $time,
            'timeStr' => $timeStr,
        ]);
    }

    public function test(){
        #return (date('Y-m-d'));
        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        $salon = Salon::find(2);
        $st_date = "2022-09-21";
        $ed_date = "2022-09-27";
        $step_time = 30;
        $course = Course::find(2);

        
        Log::debug('test:');
        Log::debug(
            ControllersBookingController::getCanBookList($allBookings, $allDefaultCapacities,$allTempCapacities,$salon , $step_time , $st_date, $ed_date, $course)
        );
        return (date('Y-m-d'));

    }

    public function showAllBookings(){

        return "すべての予約をみせます。";
    }

    public function getAcceptableCount(){
        $acceptableCount = [];
        $st_date = date('Y-m-d');
        $salons = Salon::all();
        $salon = $salons->find(1);

        
        $ed_date = Util::addDays($st_date,7);
        $step_time = 30;

        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        
        $times = [];
        $timesNum = [];
        $st_time = $salon -> st_time;
        $ed_time = $salon -> ed_time;
        
        for($time = $st_time; $time < $ed_time; $time = $time + $step_time){
            $str_time =Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }

        $days = [];
        for($i = $st_date; $i <= $ed_date ;  $i = Util::addDays($i,1)){
            $days[$i] = $i;
        }


        $capacities =
        ControllersBookingController::getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities,$allTempCapacities,$salon , $step_time , $st_date, $ed_date);
        Log::debug($capacities);



        return view('admin.bookings.acceptCount',[
            'date' => date('Y-m-d'),
            'times' => $times,
            'days'=> $days,
            'capacities'=> $capacities,
            'timesNum' => $timesNum,
            'salons' => $salons,
            'selectedSalon' => $salon,
        ]);
    }

    public function getAcceptableCountWithSalonDate(Request $request){
        $acceptableCount = [];
        $st_date = $request -> st_date;
        $salons = Salon::all();
        $salon = $salons->find($request -> salon);
        
        $ed_date = Util::addDays($st_date,7);
        $step_time = 30;

        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        
        $times = [];
        $timesNum = [];
        $st_time = $salon -> st_time;
        $ed_time = $salon -> ed_time;
        
        for($time = $st_time; $time < $ed_time; $time = $time + $step_time){
            $str_time =Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }

        $days = [];
        for($i = $st_date; $i <= $ed_date ;  $i = Util::addDays($i,1)){
            $days[$i] = $i;
        }


        $capacities =
        ControllersBookingController::getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities,$allTempCapacities,$salon , $step_time , $st_date, $ed_date);
        Log::debug($capacities);



        return view('admin.bookings.acceptCount',[
            'date' => $st_date,
            'times' => $times,
            'days'=> $days,
            'capacities'=> $capacities,
            'timesNum' => $timesNum,
            'salons' => $salons,
            'selectedSalon' => $salon,
        ]);
    }

    public function test2(){
        $defaultCapacities = DefaultCapacity::all();
        $salons = Salon::all();
        
        Log::debug('defaultcapacity:' . $defaultCapacities );

        $sdate = '2022-09-15';
        $edate = '2022-10-15';

        foreach($salons as $salon){            
            for($i = $sdate; $i <= $edate ;  $i = Util::addDays($i,1)){
                $capacity = ControllersBookingController::getDefaultCapacityOfTheDayAndSalon( $defaultCapacities,$i,$salon -> id);
                Log::debug('date:' . $i . ' salon:' . $salon -> salon_name .' capacity:' . $capacity);
            }
        }

        Log::debug('defaultcapacity:' .$defaultCapacities);

        return (date('Y-m-d'));
    }

    public static function getBookingCountsOfMultiDaysFromStartDateToEndDate($bookings,$salon,$step_time,$st_date, $ed_date){
        $bookingCountsOfMultiDaysFromStartDateToEndDate = [];

        for($day = $st_date; $day <= $ed_date; $day = Util::addDays($day,1)){
            $countsForADay = ControllersBookingController::getTimeTableFromSalonDay($bookings,$salon,$day,$step_time);
            $bookingCountsOfMultiDaysFromStartDateToEndDate[$day] = $countsForADay;
        }
        return $bookingCountsOfMultiDaysFromStartDateToEndDate;
    }

    public function getTodayAllBookings(){
        $bookings = Booking::orderBy('salon_id')
        -> orderBy('date')
        -> orderBy('st_time')
        -> get();
        session(['bookings'=>$bookings]);

        $bookings = $bookings
        -> sortBy(['salon_id'],['date'],['st_time']);

        return view('admin.bookings.index',[
            'bookings' => $bookings,
        ]);
    }

    public function getAllBookingsOfSalonAndDate(Request $request){
        $salons = Salon::all();
        $courses = CourseMaster::all();
        
        if($request-> has('salon')){
            $salon = $salons -> find($request -> salon);
        } else{
            $salon = $salons -> find(2);
        }
        
        if($request-> has('date')){
            $date = $request -> date;
        } else{
            $date = date('Y-m-d');
        }

        $date = $request -> date;
        $bookings = Booking::where('date' , $date) 
                    -> where('salon_id', $salon -> id) 
                    -> orderBy('st_time')
                    -> get();

        Log::debug('salon_id:' . $salon -> id . ' date:' . $date); 

        $st_time = $salon -> st_time;
        $ed_time = $salon -> ed_time;
        $step_time = 30;

        $times = [];
        $timesNums =[];

        for($time = $st_time; $time < $ed_time; $time = $time + $step_time){
            $str_time =Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNums[$str_time] = $time;
        }

        return view('admin.bookings.index',[
            'bookings' => $bookings,
            'checkdate' => $date,
            'selectedSalon' => $salon,
            'salons' => $salons,
            'times' => $times,
            'timesNums' => $timesNums,
            'courses' => $courses,
        ]);
    }

    //管理者が予約を作成
    public function adminMakeBooking(){
        $pets = Pet::all();
        $salons = Salon::all();
        $courses = Course::all();

        return view('admin.bookings.adminMakeBooking',[
            'pets' => $pets,
            'salons' => $salons,
            'today' => date('Y-m-d'),
            'courses' => $courses

        ]);
    }

    public function adminMakeBookingSave(Request $request){
        $booking = new Booking();

        $date = $request -> date;
        $st_hour = $request -> st_hour;
        $st_minute = $request -> st_minute;
        $ed_hour = $request -> ed_hour;
        $ed_minute = $request -> ed_minute;
        $pet_id = $request -> pet;
        $course_id = $request ->course;
        $price =  $request ->price;
        $salon_id = $request -> salon;
        $booking_status = 1;

        $st_time = $st_hour * 60 + $st_minute;
        $ed_time = $ed_hour * 60 + $ed_minute;

        $booking -> date = $date;
        $booking -> st_time = $st_time;
        $booking -> ed_time = $ed_time;
        $booking -> pet_id = $pet_id;
        $booking -> course_id = $course_id;
        $booking -> price = $price;
        $booking -> booking_status = $booking_status;
        $booking -> salon_id = $salon_id;
        $booking -> save();
        Log::info('管理者予約登録：(pet_id)' . $pet_id . 
            ' (course)' . $course_id . 
            '(date)' . $date  . 
            '(st_time)' . $st_time . 
            '(ed_time)' . $ed_time . 
            ('booking_status') . $booking_status);

        #Log::debug('ここでメールを送りたい。');
        Mail::to('kim.ksuke@gmail.com')
        ->send(new ContactAdminMail());
        return redirect('/admin/makebooking') -> with("success","予約を登録しました");
    }



    public function index()
    {
        //BookingController::bookingCheck();

        $owner = Auth::user();
        
        $bookings = Booking::orderBy('date')
        -> orderBy('st_time')
        -> get();
        
        return view('bookings.index',[
            'owner' => $owner,
            'bookings' => $bookings,
        ]);
    }
    
    public function selectCourse(Request $request){
        Log::debug('(function:selectCourse)');
        $owner = Auth::user();
        $pets = session('pets');
        $pet_id = $request -> pet;
        Log::debug('pets:'. $pets);
        Log::debug('pet_id:'. $pet_id);
        $pet = $pets -> find($pet_id);
        Log::debug('pet:'. $pet);
        $salons = Salon::all();
        $courses = Course::where('dogtype_id' , $pet -> dogtype_id ) -> get();
        session(['pet' => $pet,
                'courses' => $courses,
                'salons' => $salons,
            ]);
            
            return view('bookings.selectcourse',[
                'owner' => $owner,
                'pet' => $pet,
                'courses' => $courses,
                'salons' => $salons,
            ]);
    }

    public function selectCalender(Request $request){
        $owner = Auth::user();
        $pet =  session('pet');
       
        $courses = session('courses');
        $course = $courses -> find($request -> course);
        session(['course' => $course]);
        $salons = session('salons');
        $salon = $salons -> find($request -> salon);
        session(['salon' => $salon]);
        $st_time = $salon -> st_time;
        $ed_time = $salon -> ed_time;
       
        $step_time = 30;

        //初期値は本日より1週間分のデータを取得
        $st_date = date('2022-10-22');
        $st_date = date('Y-m-d');
        $ed_date =  Util::addDays($st_date,6);

        $times = [];
        $timesNum = [];
        for($time = $st_time; $time < $ed_time; $time = $time + $step_time){
            $str_time =Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }

        $days = [];
        for($i = $st_date; $i <= $ed_date ;  $i = Util::addDays($i,1)){
            $days[$i] = $i;
        }

        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();

        $capacities =
        ControllersBookingController::getCanBookList($allBookings, $allDefaultCapacities,$allTempCapacities,$salon , $step_time , $st_date, $ed_date, $course);
        Log::debug($capacities);

        session([
            'course' => $course,
            'salon' => $salon,
        ]);

        $beforeDate = Util::addDays($st_date,-7);
        $afterDate = Util::addDays($st_date,7);

        return view('bookings.booking_calender',[
            'date' => $st_date,
            'before_date' => $beforeDate,
            'after_date' => $afterDate,
            'owner' => $owner,
            'pet' => $pet,
            'course' => $course,
            'salon' => $salon,
            'times' => $times,
            'days'=> $days,
            'capacities'=> $capacities,
            'timesNum' => $timesNum,
        ]);
    }

    public function selectCalenderSalonAndDate(Request $request , $salon, $st_date){
        $owner = Auth::user();
        $pet =  session('pet');
        $course = session('course');
        $salon = session('salon');

        $st_time = $salon -> st_time;
        $ed_time = $salon -> ed_time;
       
        $step_time = 30;

        //指定日より1週間分のデータを取得
        $ed_date =  Util::addDays($st_date,6);

        $times = [];
        $timesNum = [];
        for($time = $st_time; $time < $ed_time; $time = $time + $step_time){
            $str_time =Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }

        $days = [];
        for($i = $st_date; $i <= $ed_date ;  $i = Util::addDays($i,1)){
            $days[$i] = $i;
        }

        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();

        $capacities =
        ControllersBookingController::getCanBookList($allBookings, $allDefaultCapacities,$allTempCapacities,$salon , $step_time , $st_date, $ed_date, $course);
        Log::debug($capacities);

        session([
            'course' => $course,
            'salon' => $salon,
        ]);

        $beforeDate = Util::addDays($st_date,-7);
        $afterDate = Util::addDays($st_date,7);

        return view('bookings.booking_calender',[
            'date' => $st_date,
            'before_date' => $beforeDate,
            'after_date' => $afterDate,
            'owner' => $owner,
            'pet' => $pet,
            'course' => $course,
            'salon' => $salon,
            'times' => $times,
            'days'=> $days,
            'capacities'=> $capacities,
            'timesNum' => $timesNum,
        ]);
    }

    public function confirm(Request $request){
        $owner = Auth::user();
        $pet =  session('pet');
        $course =  session('course');
        $date = $request -> date;
        $time = $request -> time;
        session([
            'date' => $date,
            'time' => $time,
        ]);
        return view('bookings.confirm',[
            'owner' => $owner,
            'pet' => $pet,
            'course' => $course,
            'date' => $date,
            'time' => $time,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $owner = Auth::user();
        session([
            'owner' => $owner,
        ]);
        $pets = Pet::where('owner_id' , $owner -> id) -> get();
        session(['pets' => $pets]);
        //return redirect('/selectcourse');

        
        return view('bookings.selectpet',[
            'pets' => $pets,
            'owner' => $owner,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking = new Booking();

        $date = session('date');
        $st_time = session('time');
        $cut_time = session('course') -> minute;
        $ed_time = $st_time + $cut_time;
        $pet_id = session('pet') -> id;
        $course_id = session('course') -> id;
        $price =  session('course') -> price;
        $salon_id = session('salon') -> id;
        $booking_status = 1;

        $booking -> date = $date;
        $booking -> st_time = $st_time;
        $booking -> ed_time = $ed_time;
        $booking -> pet_id = $pet_id;
        $booking -> course_id = $course_id;
        $booking -> price = $price;
        $booking -> booking_status = $booking_status;
        $booking -> salon_id = $salon_id;

        $booking -> save();
        Log::info('予約登録：(pet_id)' .session('pet') -> id . ' (course)' . session('course') -> id . '(date)' . session('date') ) . '(st_time)' . $st_time . '(ed_time)' . $ed_time . ('booking_status') . $booking_status;
        Mail::to('kim.ksuke@gmail.com')
        ->send(new ContactAdminMail());

        return redirect('/bookings') -> with('success','予約を登録をしました。');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking -> delete();
        $user = Auth::user();
        Log::info('User ' . $user -> id . 'canceled booking_id=' . $id .' '.$booking->getBookingInfo());

        return redirect('/bookings')
        -> with('success','予約のキャンセルに成功しました。');
    }
/*
     public static function bookingCheck(){
        $date = date('Y-m-d');
         for($i =1 ;$i++; $i<7){
             $date = $date + $i;
             Log::debug($date);
             BookingController::getDateBookingCount(date($date));
        }
     }

     
*/
     public static function getTempCapacityFromSalonDateTime($allTempCapacities, $salon , $date, $time){
        $salon_id = $salon -> id;

        $tempCapacities = TempCapacity:: where('salon_id', $salon_id)
                -> where('st_date','<=' , $date)
                -> where('ed_date','>=' , $date)
                -> where('st_time','<=' , $time)
                -> where('ed_time','>=' , $time) -> first();

        Log::debug('salon:'.$salon -> salon_name . ' date:' . $date . ' time:' . $time);

        return $tempCapacities;
     }

     public static function getAllBookingsByDate($bookings , $date){
        $dateBookings = $bookings 
            -> where('date' , $date) 
            -> sortBy('st_time');
        return $dateBookings;
     }

     public static function getTimeTableFromSalonDay($bookings,$salon,$date,$step_time){
        $counts = [];

        //サロン情報を格納
        $salon_id = $salon -> id;
        $open_time = $salon -> st_time;
        $close_time = $salon -> ed_time;

        $time = $open_time;

        while($time < $close_time){
            $count = ControllersBookingController::getBookingCountFromSalonDayTimeMinute($bookings,$salon_id,$date , $time);
            $counts[$time] = $count;

            $time = $time + $step_time;
        }

        return $counts;
     }

     //$cutTimeは、終了時間を算出するために必要
     public static function getBookingCountFromSalonDayTimeMinute($bookings, $salon_id , $date, $time){
        //DBへの繰り返しアクセスを防ぐため、予約データを呼び出し時に受け取るようにする。
        
        $count = 
            $bookings
            -> where('date' , $date)
            -> where('salon_id',$salon_id)
            -> where('st_time' , '<=', $time)
            -> where('ed_time' , '>', $time)
            -> count();

        return $count;
     }

     public static function getDefaultCapacityOfTheDayAndSalon($allDefaultCapacities,$date, $salon){
        //DBへの繰り返しアクセスを防ぐため、デフォルトデータを呼び出し時に受け取るようにする。
        $salon_id = $salon -> id;
        $capacity = 
            $allDefaultCapacities
                    -> where('salon_id' , $salon_id)
                    -> where('st_date' , '<=', $date)
                    -> sortByDesc('st_date')
                    ->first();
            
            return $capacity -> capacity;
     }

     //特定日付範囲の各日の枠数を取得
     public static function getCapacitiesFromMultiDays($allDefaultCapacities, $allTempCapacities, $salon,$step_time,$st_date, $ed_date){
        $capacitiesFromMultiDays = [];

        for($date = $st_date; $date <= $ed_date ;  $date = Util::addDays($date,1)){
            $capacitiesFromMultiDays[$date] =  ControllersBookingController::getCapacityFromDay($allDefaultCapacities,$allTempCapacities,$date, $salon,$step_time);
        }
        return $capacitiesFromMultiDays;
     }

     //開始日と終了日を指定して店舗の枠数を取得
    public static function getCapacitiesOfMultiDays($allDefaultCapacities,$allTempCapacities, $st_date , $ed_date, $salon , $step_time){
        $capacitiesFromMultiDays = [];
        for($date = $st_date; $date <= $ed_date ;  $date = Util::addDays($date,1)){
            $capacitiesFromMultiDays[$date] =
            ControllersBookingController::getCapacityFromDay($allDefaultCapacities,$allTempCapacities, $date, $salon,$step_time);
        }

        return $capacitiesFromMultiDays;
    }

     //店舗の1日の枠数を取得
     public static function getCapacityFromDay($allDefaultCapacities,$allTempCapacities, $date, $salon , $step_time){
        $open_time = $salon -> st_time;
        $close_time = $salon -> ed_time;
        $capacitiesOfTheDay = [];

        #Log::debug("(395)Date:" . $date . ' Salon:' . $salon -> salon_name);
        #Log::debug("allTempCapacities:" . $allTempCapacities);

        $tempCapacitiesOfTheDate = $allTempCapacities
            -> where('st_date' , '<=', $date)
            -> where('ed_date' , '>=', $date);


        #Log::debug('$date:' . $date);
        #Log::debug('$tempCapacitiesOfTheDate :' . $tempCapacitiesOfTheDate);

        //DBから店舗のデフォルト受け入れ枠データ
        for($time = $open_time ; $time < $close_time; $time = $time + $step_time){
            $capacitiesOfTheDay[$time] 
            = ControllersBookingController::getDefaultCapacityOfTheDayAndSalon($allDefaultCapacities,$date,$salon);

            foreach($tempCapacitiesOfTheDate as $capacity){
                #Log::debug('$capacity:' . $capacity);
                $st_time = $capacity -> st_time;
                $ed_time = $capacity -> ed_time;
                
                #Log::debug('$time:' . $time.' $st_time:' . $st_time .' $ed_time:' . $ed_time);
                $reWrite = true;

                if($st_time > $time){
                    $reWrite = false;
                }

                if($ed_time < $time){
                    $reWrite = false;
                }
                Log::debug(' ReWrite:' . $reWrite);
                Log::debug($ed_time >= $time);
                if($reWrite){
                    Log::debug('$time:' . $time.' $st_time:' . $st_time .' $ed_time:' . $ed_time .' Rewirte:');
                    $capacitiesOfTheDay[$time] = $capacity -> capacity;
                } else{
                    Log::debug('$time:' . $time.' $st_time:' . $st_time .' $ed_time:' . $ed_time .' No Rewirte:');
                    
                }

            }
            /*
            //サロンと日時を指定して、臨時の枠を取得する。
            Log::debug('サロンと日時を指定して、臨時の枠を取得する。');
            $tempCapacity = ControllersBookingController::getTempCapacityFromSalonDateTime($allTempCapacities, $salon , $date, $time);

            Log::debug('$salon_id:'. $salon -> id .' $time:' . $time);
            Log::debug('$tempCapacities:' . $tempCapacity);

            if(($tempCapacity -> isEmpty() != true)) {
                $capacitiesOfTheDay[$time] = $tempCapacity -> capacity;
            }
            */

        }

        return $capacitiesOfTheDay;

        $capacities = [];
        
        //臨時枠を取得
        $tempCapacities = '';

        //if(定休日){枠を全部ゼロ}else{デフォルトを入れる}

        //臨時枠があればそちらを優先する。上書き。

        return $capacitiesOfTheDay;
     }

    public static function getOtherCapacitiesOfTheDate($dateBookingsCount, $dateCapacitiesCount,$salon , $step_time){
        $otherCapacitiesOfTheDate = [];
        $st_time = $salon -> st_time;
        $ed_time = $salon -> ed_time;

        for($time = $st_time ; $time<$ed_time ; $time = $time + $step_time){
            $bookingCount = $dateBookingsCount[$time];
            $capacity     = $dateCapacitiesCount[$time];

            $otherCapacitiesOfTheDate[$time] = $capacity - $bookingCount;
        }

        return $otherCapacitiesOfTheDate;
    }

    public static function getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities,$allTempCapacities,$salon , $step_time , $st_date, $ed_date){
        $getOtherCapacitiesOfMultiDate = [];

        for($date = $st_date; $date <= $ed_date ;  $date = Util::addDays($date,1)){
            $dateBookingCount 
            = ControllersBookingController::getBookingCountsOfMultiDaysFromStartDateToEndDate($allBookings,$salon,$step_time,$st_date,$ed_date)[$date];
        
            $dateCapacity 
            =ControllersBookingController::getCapacitiesOfMultiDays($allDefaultCapacities,$allTempCapacities, $st_date,$ed_date, $salon,$step_time)[$date];

            $otherCapacitiesOfTheDate 
                = ControllersBookingController::getOtherCapacitiesOfTheDate($dateBookingCount, $dateCapacity, $salon, $step_time);

            $getOtherCapacitiesOfMultiDate[$date] = $otherCapacitiesOfTheDate;
        }

        return $getOtherCapacitiesOfMultiDate;
    }

    public static function getCanBookList($allBookings, $allDefaultCapacities,$allTempCapacities,$salon , $step_time , $st_date, $ed_date, $course){
        Log::debug('(start) getCanBookList' );
        Log::debug('$course:' . $course ->id . ' minute:' . $course -> minute);
        $getOtherCapacitiesOfMultiDate 
        = ControllersBookingController::getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities,$allTempCapacities,$salon , $step_time , $st_date, $ed_date);
        $cut_time = $course -> minute;
        $st_time = $salon -> st_time;
        $ed_time = $salon -> ed_time;
        
        $canBookTimeListOfMultiDay = [];
        for($date = $st_date; $date <= $ed_date ;  $date = Util::addDays($date,1)){
            if($date <= date('Y-m-d')){
                for($time=$st_time;$time<$ed_time;$time = $time+ $step_time){
                    $canBookTimeListOfADay[$time] = 0;
                }
                $canBookTimeListOfMultiDay[$date] = $canBookTimeListOfADay;
                continue;
            }

            if($date >= Util::addDays(date('Y-m-d'),30)){
                for($time=$st_time;$time<$ed_time;$time = $time+ $step_time){
                    $canBookTimeListOfADay[$time] = 0;
                }
                $canBookTimeListOfMultiDay[$date] = $canBookTimeListOfADay;
                continue;
            }
            #Log::debug($getOtherCapacitiesOfMultiDate[$date]);
            $getOtherCapacitiesOfADate 
                = $getOtherCapacitiesOfMultiDate[$date];
                //Log::debug('$getOtherCapacitiesOfADate');
                //Log::debug(' $date:' . $date);
                //Log::debug($getOtherCapacitiesOfADate);
                
            $canBookTimeListOfADay = [];
            for($time=$st_time;$time<$ed_time;$time = $time+ $step_time){
                //カットの終了時間が閉店時間を超えたら枠0扱い
                $cutting_ed_time = $time + $cut_time;
                //Log::debug(' time:'. $time . ' cuttingEndingTIme:' . $cutting_ed_time);
                if($cutting_ed_time > $ed_time){
                    //Log::debug('閉店時間をすぎる');
                    $min_count  = 0;
                    
                }else{
                    //Log::debug('閉店時間をすぎない');
                    $min_count = $getOtherCapacitiesOfADate [$time];
                    //Log::debug('$min_count初期化::' . $min_count . ' $time:' .$time);
                    for($cutting_time = $time;$cutting_time<$cutting_ed_time;$cutting_time = $cutting_time +$step_time){
                        $temp_count = $getOtherCapacitiesOfADate [$cutting_time];
                        if($temp_count<$min_count){
                            $min_count = $temp_count;
                        }
                    }
                }
                $canBookTimeListOfADay[$time] = $min_count;
                //Log::debug(' date:'.$date.' time' . $time .' min_count:'. $min_count);
            }
            //Log::debug('$canBookTimeListOfADay');
            //Log::debug($canBookTimeListOfADay);
            $canBookTimeListOfMultiDay[$date] = $canBookTimeListOfADay;
                
                
            }
            
            //Log::debug( $canBookTimeListOfMultiDay);
            //Log::debug('(end) getCanBookList' );
            return $canBookTimeListOfMultiDay;
        }
        
    }
    
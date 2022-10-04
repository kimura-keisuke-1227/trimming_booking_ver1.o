<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Salon;
use App\Models\DefaultCapacity;
use App\Models\RegularHoliday;
use App\Models\TempCapacity;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\classes\Util;
use App\Models\CourseMaster;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAdminMail;
use App\Models\Dogtype;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**************************************************************
     *
     *   テスト用
     *
     ***************************************************************/

     public function test(){
        $bookings = Booking::with('pet.user')
        ->with('course.coursemaster')
        ->with('pet.dogtype')
        #->where('pet.user.id' ,2)
        ->get();
        Log::debug($bookings);
        return view('test', [
            'bookings' => $bookings
        ]);
    }

    /**************************************************************
    *
    *   非会員用
    *
    ***************************************************************/

    public function startNonUserBooking(){
        $dogTypes = Dogtype::all();
        session([
            'dogTypes' => $dogTypes
        ]);
        return view('nonMember.nonMemberBooking1',[
            'dogtypes' => $dogTypes,
        ]);
    }

    public function startNonUserBookingEntry(Request $request){
        
        $dogtype = session('dogTypes') -> find($request ->dogtype);

        $owner_last_name = $request ->dogtype;
        $owner_first_name = $request ->dogtype;
        $owner_last_name_kana = $request ->dogtype;
        $owner_first_name_kana = $request ->dogtype;
        $pet_name = $request ->dogtype;
        $salons = Salon::all();

        $courses = Course::where('dogtype_id', $dogtype->id) -> get();
        
        session([
            'dogtype' => $dogtype,
            'owner_last_name' => $owner_last_name,
            'owner_first_name' => $owner_first_name,
            'owner_last_name_kana' => $owner_last_name_kana,
            'owner_first_name_kana' => $owner_first_name_kana,
            'pet_name' => $pet_name,
            'courses' => $courses,
            'salons' => $salons,
        ]);

        return view('nonMember.nonMemberSelectcourse',[
            'salons' => $salons,
            'courses' => $courses,
            '$dogType' => $dogtype,
        ]);

        /*
        return view('nonMember.nonMemberBooking1',[
            'dogtypes' => $dogTypes,
        ]);
        */
    }

    public function startNonUserBookingSelectCalender(Request $request){
        
        $dogType =session('dogtype');
        $salons = session('salons');
        $salon_id = $request -> salon;
        $salon = $salons -> find($salon_id);
        
        $course_id = $request -> course;
        $course = session('courses') -> find($course_id);
        Log::debug(__FUNCTION__ . ' course_id:' . $course_id);

        session([
            'salon' => $salon,
            'course' => $course,
        ]);

        $today = date('Y-m-d');

        $beforeDate = Util::addDays($today,-7);
        $afterDate = Util::addDays($today,7);

        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        $step_time = $this -> getSetting(30,'step_time',true);

        $times = [];
        $timesNum = [];
        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $str_time = Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }

        $days = [];
        for ($i = $today; $i <= Util::addDays($afterDate,-1); $i = Util::addDays($i, 1)) {
            $days[$i] = $i;
        }

        $st_date = $today;
        $ed_date = Util::addDays($afterDate,-1);

        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        $allRegularHoliday = RegularHoliday::all();

        Log::debug(__FUNCTION__ . ' course' . $course_id);

        $capacities =
            $this->getCanBookList($allBookings, $allDefaultCapacities, $allRegularHoliday, $allTempCapacities, $salon, $step_time, $st_date, $ed_date, $course);

        return view('nonMember.nonMember_booking_calender',[
            'salon' => $salon,
            'dogtype' => $dogType,
            'before_date' => $beforeDate,
            'after_date' => $afterDate,
            'days' => $days,
            'times' => $times,
            'timesNum' => $timesNum,
            'capacities' =>$capacities
        ]);
    }

    public function saveNonMemberBooking(Request $request){
        Log::debug(__FUNCTION__ . ' 登録なしの予約');
        return '予約する';
    }


    /**************************************************************
     *
     *   ユーザー用
     *
     ***************************************************************/

    //ログイン中のユーザーの予約一覧を表示
    public function index()
    {
        //BookingController::bookingCheck();

        $owner = Auth::user();
        $showBookingsAfterNDays = $this -> getSetting(30,'showBookingsAfterNDays',true);

        $bookings = Booking::with('pet.user')
        ->with('course.coursemaster')
        ->with('pet.dogtype')
        ->where('date' , '>' , Util::addDays(date('Y-m-d'),-$showBookingsAfterNDays))
        ->orderBy('date')
        ->orderBy('st_time')
        ->get();

        return view('bookings.index', [
            'owner' => $owner,
            'bookings' => $bookings,
            'showBookingsAfterNDays' => $showBookingsAfterNDays,
        ]);
    }

    //予約するペットを選択する
    public function create()
    {
        $owner = Auth::user();
        session([
            'owner' => $owner,
        ]);
        $pets = Pet::where('owner_id', $owner->id)->get();
        session(['pets' => $pets]);

        return view('bookings.selectpet', [
            'pets' => $pets,
            'owner' => $owner,
        ]);
    }

    //コースを選択する
    public function selectCourse(Request $request)
    {
        Log::debug('(function:selectCourse)');
        $owner = Auth::user();
        $pets = session('pets');
        $pet_id = $request->pet;
        $pet = $pets->find($pet_id);
        $salons = Salon::all();
        $courses = Course::where('dogtype_id', $pet->dogtype_id)->get();
        session([
            'pet' => $pet,
            'courses' => $courses,
            'salons' => $salons,
        ]);

        return view('bookings.selectcourse', [
            'owner' => $owner,
            'pet' => $pet,
            'courses' => $courses,
            'salons' => $salons,
        ]);
    }

    public function selectCalender(Request $request)
    {
        $owner = Auth::user();
        $pet =  session('pet');

        $courses = session('courses');
        $course = $courses->find($request->course);
        session(['course' => $course]);
        $salons = session('salons');
        $salon = $salons->find($request->salon);
        session(['salon' => $salon]);
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;

        $step_time = $this -> getSetting(30,'step_time',true);


        //初期値は本日より1週間分のデータを取得
        $st_date = date('Y-m-d');
        $ed_date =  Util::addDays($st_date, 6);

        $times = [];
        $timesNum = [];
        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $str_time = Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }

        $days = [];
        for ($i = $st_date; $i <= $ed_date; $i = Util::addDays($i, 1)) {
            $days[$i] = $i;
        }

        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        $allRegularHoliday = RegularHoliday::all();

        $capacities =
            $this->getCanBookList($allBookings, $allDefaultCapacities, $allRegularHoliday, $allTempCapacities, $salon, $step_time, $st_date, $ed_date, $course);
        #Log::debug($capacities);

        session([
            'course' => $course,
            'salon' => $salon,
        ]);

        $beforeDate = Util::addDays($st_date, -7);
        $afterDate = Util::addDays($st_date, 7);

        return view('bookings.booking_calender', [
            'date' => $st_date,
            'before_date' => $beforeDate,
            'after_date' => $afterDate,
            'owner' => $owner,
            'pet' => $pet,
            'course' => $course,
            'salon' => $salon,
            'times' => $times,
            'days' => $days,
            'capacities' => $capacities,
            'timesNum' => $timesNum,
        ]);
    }

    public function confirmBooking(Request $request, $date, $time)
    {
        $owner = Auth::user();
        $pet =  session('pet');
        $course =  session('course');
        $date = $request->date;
        $time = $request->time;
        $timeStr = Util::minuteToTime($time);
        session([
            'date' => $date,
            'time' => $time,
        ]);
        return view('bookings.confirm', [
            'owner' => $owner,
            'pet' => $pet,
            'course' => $course,
            'date' => $date,
            'time' => $time,
            'timeStr' => $timeStr,
        ]);
    }

    //予約の最終確認
    /*
    public function confirm(Request $request)
    {
        $owner = Auth::user();
        $pet =  session('pet');
        $course =  session('course');
        $date = $request->date;
        $time = $request->time;
        session([
            'date' => $date,
            'time' => $time,
        ]);
        return view('bookings.confirm', [
            'owner' => $owner,
            'pet' => $pet,
            'course' => $course,
            'date' => $date,
            'time' => $time,
        ]);
    }
    */

    //削除する予約の確認画面
    public function deleteConfirm($bookingID)
    {
        $owner = Auth::user();
        $booking = Booking::find($bookingID);

        $owner_id = $owner->id;
        if (!is_null($booking)) {
            $booking_owner_id = $booking->pet->user->id;
        }

        if ((is_null($booking) or ($owner_id != $booking_owner_id))) {
            return redirect('/bookings')
                ->with('error', '該当の予約が存在しません。');
        }

        return view('bookings.cancelConfirm', [
            'booking' => $booking
        ]);
    }

    //予約のキャンセル処理
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        $user = Auth::user();
        Log::info('User ' . $user->id . 'canceled booking_id=' . $id . ' ' . $booking->getBookingInfo());

        return redirect('/bookings')
            ->with('success', '予約をキャンセルしました。');
    }



    /**************************************************************
     *
     *   管理者用
     *
     ***************************************************************/

    public function getTodayAllBookings()
    {
        $bookings = Booking::orderBy('salon_id')
            ->orderBy('date')
            ->orderBy('st_time')
            ->get();
        session(['bookings' => $bookings]);

        $bookings = $bookings
            ->sortBy(['salon_id'], ['date'], ['st_time']);

        return view('admin.bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    //管理者用　サロンと日付を指定して1日の予約を取得
    public function getAllBookingsOfSalonAndDate(Request $request)
    {
        $salons = Salon::all();
        $courses = CourseMaster::all();

        if ($request->has('salon')) {
            $salon = $salons->find($request->salon);
        } else {
            $salon = $salons->find(1);
        }

        if ($request->has('date')) {
            $date = $request->date;
        } else {
            $date = date('Y-m-d');
        }

        $date = $request->date;
        $bookings = Booking::where('date', $date)
            ->where('salon_id', $salon->id)
            ->orderBy('st_time')
            ->get();

        Log::debug('salon_id:' . $salon->id . ' date:' . $date);

        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        $step_time = $this -> getSetting(30,'step_time',true);

        $times = [];
        $timesNums = [];

        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $str_time = Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNums[$str_time] = $time;
        }

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'checkdate' => $date,
            'selectedSalon' => $salon,
            'salons' => $salons,
            'times' => $times,
            'timesNums' => $timesNums,
            'courses' => $courses,
        ]);
    }

    //管理者が予約を作成する画面
    public function adminMakeBooking()
    {
        $pets = Pet::all();
        $salons = Salon::all();
        $courses = Course::all();

        return view('admin.bookings.adminMakeBooking', [
            'pets' => $pets,
            'salons' => $salons,
            'today' => date('Y-m-d'),
            'courses' => $courses

        ]);
    }

    //管理者による予約の登録　保存処理
    public function adminMakeBookingSave(Request $request)
    {
        $booking = new Booking();

        $date = $request->date;
        $st_hour = $request->st_hour;
        $st_minute = $request->st_minute;
        $ed_hour = $request->ed_hour;
        $ed_minute = $request->ed_minute;
        $pet_id = $request->pet;
        $course_id = $request->course;
        $price =  $request->price;
        $salon_id = $request->salon;
        $booking_status = 1;

        $st_time = $st_hour * 60 + $st_minute;
        $ed_time = $ed_hour * 60 + $ed_minute;

        $booking->date = $date;
        $booking->st_time = $st_time;
        $booking->ed_time = $ed_time;
        $booking->pet_id = $pet_id;
        $booking->course_id = $course_id;
        $booking->price = $price;
        $booking->booking_status = $booking_status;
        $booking->salon_id = $salon_id;
        $booking->save();

        Log::info('管理者予約登録：(pet_id)' . $pet_id .
            ' (course)' . $course_id .
            '(date)' . $date  .
            '(st_time)' . $st_time .
            '(ed_time)' . $ed_time .
            ('booking_status') . $booking_status);

        #Log::debug('ここでメールを送りたい。');
        Mail::to('kim.ksuke@gmail.com')
            ->send(new ContactAdminMail());
        return redirect('/admin/makebooking')->with("success", "予約を登録しました");
    }


    //管理者用　空き枠の取得
    public function getAcceptableCount()
    {
        $acceptableCount = [];
        $st_date = date('Y-m-d');
        $salons = Salon::all();
        $salon = $salons->find(1);


        $ed_date = Util::addDays($st_date, 7);
        $step_time = $this -> getSetting(30,'step_time',true);

        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allRegularHolidays = RegularHoliday::all();
        $allTempCapacities = TempCapacity::all();

        $times = [];
        $timesNum = [];
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;

        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $str_time = Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }

        //日付を開始日から終了日まで1日ずつ格納
        $days = [];
        for ($i = $st_date; $i <= $ed_date; $i = Util::addDays($i, 1)) {
            $days[$i] = $i;
        }

        $capacities =
            $this->getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date);
        #Log::debug($capacities);

        return view('admin.bookings.acceptCount', [
            'date' => date('Y-m-d'),
            'times' => $times,
            'days' => $days,
            'capacities' => $capacities,
            'timesNum' => $timesNum,
            'salons' => $salons,
            'selectedSalon' => $salon,
        ]);
    }

    public function getAcceptableCountWithSalonDate(Request $request)
    {
        $acceptableCount = [];
        $st_date = $request->st_date;
        $salons = Salon::all();
        $salon = $salons->find($request->salon);

        $ed_date = Util::addDays($st_date, 7);
        $step_time = $this -> getSetting(30,'step_time',true);

        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        $allRegularHolidays = RegularHoliday::all();

        $times = [];
        $timesNum = [];
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;

        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $str_time = Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }

        $days = [];
        for ($i = $st_date; $i <= $ed_date; $i = Util::addDays($i, 1)) {
            $days[$i] = $i;
        }


        $capacities =
            $this->getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date);

        return view('admin.bookings.acceptCount', [
            'date' => $st_date,
            'times' => $times,
            'days' => $days,
            'capacities' => $capacities,
            'timesNum' => $timesNum,
            'salons' => $salons,
            'selectedSalon' => $salon,
        ]);
    }



    public function selectCalenderSalonAndDate(Request $request, $salon, $st_date)
    {
        $owner = Auth::user();
        $pet =  session('pet');
        $course = session('course');
        $salon = session('salon');

        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;

        $step_time = $this -> getSetting(30,'step_time',true);

        //指定日より1週間分のデータを取得
        $ed_date =  Util::addDays($st_date, 6);

        $times = [];
        $timesNum = [];
        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $str_time = Util::minuteToTime($time);
            $times[$time] = $str_time;
            $timesNum[$str_time] = $time;
        }

        $days = [];
        for ($i = $st_date; $i <= $ed_date; $i = Util::addDays($i, 1)) {
            $days[$i] = $i;
        }

        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        $allRegularHoliday = RegularHoliday::all();

        $capacities =
            $this->getCanBookList($allBookings, $allDefaultCapacities, $allRegularHoliday, $allTempCapacities, $salon, $step_time, $st_date, $ed_date, $course);
        #Log::debug($capacities);

        session([
            'course' => $course,
            'salon' => $salon,
        ]);

        $beforeDate = Util::addDays($st_date, -7);
        $afterDate = Util::addDays($st_date, 7);

        return view('bookings.booking_calender', [
            'date' => $st_date,
            'before_date' => $beforeDate,
            'after_date' => $afterDate,
            'owner' => $owner,
            'pet' => $pet,
            'course' => $course,
            'salon' => $salon,
            'times' => $times,
            'days' => $days,
            'capacities' => $capacities,
            'timesNum' => $timesNum,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


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
        $cut_time = session('course')->minute;
        $ed_time = $st_time + $cut_time;
        $pet_id = session('pet')->id;
        $course_id = session('course')->id;
        $price =  session('course')->price;
        $salon_id = session('salon')->id;
        $booking_status = 1;

        $booking->date = $date;
        $booking->st_time = $st_time;
        $booking->ed_time = $ed_time;
        $booking->pet_id = $pet_id;
        $booking->course_id = $course_id;
        $booking->price = $price;
        $booking->booking_status = $booking_status;
        $booking->salon_id = $salon_id;

        $booking->save();
        Log::info(__FUNCTION__ . ' 予約登録：(pet_id)' . session('pet')->id . ' (course)' . session('course')->id . '(date)' . session('date')) . '(st_time)' . $st_time . '(ed_time)' . $ed_time . ('booking_status') . $booking_status;
        Mail::to('kim.ksuke@gmail.com')
            ->send(new ContactAdminMail());

        return redirect('/bookings')->with('success', '予約を登録をしました。');
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



    /**************************************************************
     *
     *   内部関数
     *
     ***************************************************************/
    //店舗の1日の枠数を取得
    public function getCapacityFromDay($allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $date, $salon, $step_time)
    {
        $open_time = $salon->st_time;
        $close_time = $salon->ed_time;
        $capacitiesOfTheDay = [];

        //指定日範囲内の臨時調整枠データを取得
        $tempCapacitiesOfTheDate = $allTempCapacities
            ->where('st_date', '<=', $date)
            ->where('ed_date', '>=', $date);

        //定休日の設定を取得
        $regularHolidaysOfTheSalon = $allRegularHolidays->where('salon_id', $salon->id);
        Log::debug(__FUNCTION__ . '定休日:' . $regularHolidaysOfTheSalon);

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

        return $capacitiesOfTheDay;
    }


    //特定の日付の空き枠を取得
    public static function getOtherCapacitiesOfTheDate($dateBookingsCount, $dateCapacitiesCount, $salon, $step_time)
    {
        $otherCapacitiesOfTheDate = [];
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;

        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $bookingCount = $dateBookingsCount[$time];
            $capacity     = $dateCapacitiesCount[$time];

            $otherCapacitiesOfTheDate[$time] = $capacity - $bookingCount;
        }

        return $otherCapacitiesOfTheDate;
    }

    //指定期間内の空き枠を取得
    public function getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date)
    {
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

        return $getOtherCapacitiesOfMultiDate;
    }

    //開始日から終了日までの予約可能な枠数を取得
    public function getCanBookList($allBookings, $allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date, $course)
    {
        Log::debug(__FUNCTION__ . '(start)');
        //Log::debug('$course:' . $course->id . ' minute:' . $course->minute);
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

            $deadTimeForBooking = $this-> getSetting(30,'deadTimeForBooking',true);
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

            $untilTheDayAhead = $this -> getSetting(30,'untilTheDayAhead',true);

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
        return $canBookTimeListOfMultiDay;
    }

    public static function getTempCapacityFromSalonDateTime($allTempCapacities, $salon, $date, $time)
    {
        $salon_id = $salon->id;

        $tempCapacities = TempCapacity::where('salon_id', $salon_id)
            ->where('st_date', '<=', $date)
            ->where('ed_date', '>=', $date)
            ->where('st_time', '<=', $time)
            ->where('ed_time', '>=', $time)->first();

        Log::debug(__FUNCTION__ . ' salon:' . $salon->salon_name . ' date:' . $date . ' time:' . $time);

        return $tempCapacities;
    }

    public static function getAllBookingsByDate($bookings, $date)
    {
        $dateBookings = $bookings
            ->where('date', $date)
            ->sortBy('st_time');
        return $dateBookings;
    }

    public function getTimeTableFromSalonDay($bookings, $salon, $date, $step_time)
    {
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

        return $counts;
    }

    //$cutTimeは、終了時間を算出するために必要
    public static function getBookingCountFromSalonDayTimeMinute($bookings, $salon_id, $date, $time)
    {
        //DBへの繰り返しアクセスを防ぐため、予約データを呼び出し時に受け取るようにする。

        $count =
            $bookings
            ->where('date', $date)
            ->where('salon_id', $salon_id)
            ->where('st_time', '<=', $time)
            ->where('ed_time', '>', $time)
            ->count();

        return $count;
    }

    public function getDefaultCapacityOfTheDayAndSalon($allDefaultCapacities, $date, $salon)
    {
        //DBへの繰り返しアクセスを防ぐため、デフォルトデータを呼び出し時に受け取るようにする。
        $salon_id = $salon->id;
        $capacity =
            $allDefaultCapacities
            ->where('salon_id', $salon_id)
            ->where('st_date', '<=', $date)
            ->sortByDesc('st_date')
            ->first();

        return $capacity->capacity;
    }

    //特定日付範囲の各日の枠数を取得
    public function getCapacitiesFromMultiDays($allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date)
    {
        $capacitiesFromMultiDays = [];

        for ($date = $st_date; $date <= $ed_date; $date = Util::addDays($date, 1)) {
            $capacitiesFromMultiDays[$date] =  $this->getCapacityFromDay($allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $date, $salon, $step_time);
        }
        return $capacitiesFromMultiDays;
    }

    //開始日と終了日を指定して店舗の枠数を取得
    public function getCapacitiesOfMultiDays($allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $st_date, $ed_date, $salon, $step_time)
    {
        $capacitiesFromMultiDays = [];
        for ($date = $st_date; $date <= $ed_date; $date = Util::addDays($date, 1)) {
            $capacitiesFromMultiDays[$date] =
                $this->getCapacityFromDay($allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $date, $salon, $step_time);
        }

        return $capacitiesFromMultiDays;
    }

    public function getBookingCountsOfMultiDaysFromStartDateToEndDate($bookings, $salon, $step_time, $st_date, $ed_date)
    {
        $bookingCountsOfMultiDaysFromStartDateToEndDate = [];

        for ($day = $st_date; $day <= $ed_date; $day = Util::addDays($day, 1)) {
            $countsForADay =  $this->getTimeTableFromSalonDay($bookings, $salon, $day, $step_time);
            $bookingCountsOfMultiDaysFromStartDateToEndDate[$day] = $countsForADay;
        }
        return $bookingCountsOfMultiDaysFromStartDateToEndDate;
    }

    private function getSetting($default, $setting_name, $isInt)
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

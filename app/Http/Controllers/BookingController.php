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
use App\Mail\BookingNotificationForSalon;
use App\classes\BookingsCalc;
use App\Models\NonMemberBooking;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\ErrorHandler\Debug;

//use App\Models\Dogtype;

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
        Log::info(__METHOD__ . '(start)');
        $bookings = Booking::with('pet.user')
        ->with('course.coursemaster')
        ->with('pet.dogtype')
        #->where('pet.user.id' ,2)
        ->get();
        Log::info(__METHOD__ . '(end)');
        Log::debug($bookings);
        return view('test', [
            'bookings' => $bookings
        ]);
    }

    /**************************************************************
     *
     *   ユーザー用
     *
     ***************************************************************/

    //ログイン中のユーザーの予約一覧を表示
    public function index()
    {
        Log::info(__METHOD__ . '(start)');
        //BookingController::bookingCheck();
        Log::info(__METHOD__ . '(start)');
        $owner = Auth::user();
        $showBookingsAfterNDays = Util::getSetting(30,'showBookingsAfterNDays',true);
        $bookings = Booking::with('pet.user')
        ->with('course.coursemaster')
        ->with('pet.dogtype')
        ->where('date' , '>' , Util::addDays(date('Y-m-d'),-$showBookingsAfterNDays))
        ->where('pet_id' , '>' , 0)
        ->orderBy('date')
        ->orderBy('st_time')
        ->get();
        #Log::debug(__METHOD__ . ' $bookings:' . $bookings);
        
        Log::info(__METHOD__ . '(end)');
        return view('bookings.index', [
            'owner' => $owner,
            'bookings' => $bookings,
            'showBookingsAfterNDays' => $showBookingsAfterNDays,
        ]);
    }

    //予約するペットを選択する
    public function create()
    {
        Log::info(__METHOD__ . '(start)');
        $owner = Auth::user();
        session([
            'owner' => $owner,
        ]);
        $pets = Pet::where('owner_id', $owner->id)->get();
        session(['pets' => $pets]);
        
        Log::info(__METHOD__ . '(end)');
        return view('bookings.selectpet', [
            'pets' => $pets,
            'owner' => $owner,
        ]);
    }
    
    //コースを選択する
    public function selectCourse(Request $request)
    {
        Log::info(__METHOD__ . '(start)');
        $owner = Auth::user();
        $pets = session('pets');
        $pet_id = $request->pet;
        $pet = $pets->find($pet_id);
        $salons = Salon::all();
        $courses = Course::where('dogtype_id', $pet->dogtype_id)->get();
        $message_before = $pet ->message;

        Log::debug(__METHOD__ . '( before message is "' . $message_before . '"');
        
        session([
            'pet' => $pet,
            'courses' => $courses,
            'salons' => $salons,
        ]);
        
        Log::info(__METHOD__ . '(end)');
        return view('bookings.selectcourse', [
            'owner' => $owner,
            'pet' => $pet,
            'courses' => $courses,
            'salons' => $salons,
            'message_before' => $message_before
        ]);
    }

    public function selectCalender(Request $request)
    {
        Log::info(__METHOD__ . '(start)');
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
        
        $message = $request->message;
        
        $step_time = Util::getSetting(30,'step_time',true);
        
        $util = new Util();
        
        //初期値は本日より1週間分のデータを取得
        $st_date = date('Y-m-d');
        $ed_date =  $util -> addDays($st_date, 6);
        
        
        $times = $util->getTimes($st_time,$ed_time,$step_time);
        $timesNum = $util->getTimesNum($st_time,$ed_time,$step_time);
        
        $days = $util -> getDaysList($st_date,$ed_date);
        
        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        $allRegularHoliday = RegularHoliday::all();
        
        $bookingsCalc = new BookingsCalc();
        
        $capacities =
        $bookingsCalc->getCanBookList($allBookings, $allDefaultCapacities, $allRegularHoliday, $allTempCapacities, $salon, $step_time, $st_date, $ed_date, $course);
        
        session([
            'course' => $course,
            'salon' => $salon,
            'message' => $message,
        ]);
        
        $beforeDate = Util::addDays($st_date, -7);
        $afterDate = Util::addDays($st_date, 7);
        
        Log::info(__METHOD__ . '(end)');
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
            'message' => $message,
        ]);
    }

    public function confirmBooking(Request $request, $date, $time)
    {
        Log::info(__METHOD__ . '(start)');
        $owner = Auth::user();
        $pet =  session('pet');
        $course =  session('course');
        $message =  session('message');
        $date = $request->date;
        $time = $request->time;
        $timeStr = Util::minuteToTime($time);
        session([
            'date' => $date,
            'time' => $time,
        ]);
        Log::info(__METHOD__ . '(end)');
        return view('bookings.confirm', [
            'owner' => $owner,
            'pet' => $pet,
            'course' => $course,
            'date' => $date,
            'time' => $time,
            'timeStr' => $timeStr,
            'message' => $message,
        ]);
    }
    
    //削除する予約の確認画面
    public function deleteConfirm($bookingID)
    {
        Log::info(__METHOD__ . '(start)');
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

        Log::info(__METHOD__ . '(end)');
        return view('bookings.cancelConfirm', [
            'booking' => $booking
        ]);
    }

    //予約のキャンセル処理
    public function destroy($id)
    {
        Log::info(__METHOD__ . '(start)');
        $booking = Booking::findOrFail($id);
        $booking->delete();
        $user = Auth::user();
        Log::info('User ' . $user->id . 'canceled booking_id=' . $id . ' ' . $booking->getBookingInfo());
        
        Log::info(__METHOD__ . '(end)');
        return redirect('/bookings')
            ->with('success', '予約をキャンセルしました。');
    }



    /**************************************************************
     *
     *   管理者用
     *
     ***************************************************************/

    public function adminDeleteBookingConfirm(Request $request, $bookingID){
        Log::info(__FUNCTION__ . '(start)');
        
        $booking = Booking::find($bookingID);
        Log::info(__FUNCTION__ . '(end)');
        return view('admin.bookings.deleteConfirm',[
            'booking' => $booking
        ]);
    }

    public function adminDeleteBooking(Request $request, $bookingID){
        Log::info(__METHOD__ . '(start)');
        $staff = Auth::user();
        $booking = Booking::find($bookingID);
        Log::debug($booking);

        $booking -> delete();
        Log::info(__METHOD__ . ' booking deleted ID:' . $bookingID . ' by user ID(' . $staff->id .')');
        Log::info(__METHOD__ . '(end)');
        
        //非会員の予約の場合、そちらも削除が必要。
        if($booking -> pet_id == 0){
            Log::info(__METHOD__ . ': This booking is by non member. Need to delete non member booking!');
            $nonMemberBooking = NonMemberBooking::where('booking_id' , $bookingID)-> first();
            Log::debug(__METHOD__ . ' Deleting non member booking:' . $nonMemberBooking);
            $nonMemberBooking -> delete();
        }

        return redirect() -> route('admin.checkBookings.dateAndSalon')
        ->with("success", "予約をキャンセルしました");
    }


    public function getTodayAllBookings()
    {
        $bookings = Booking::orderBy('salon_id')
            ->orderBy('date')
            ->orderBy('st_time')
            ->get();
        session(['bookings' => $bookings]);

        $bookings = $bookings
            ->sortBy(['salon_id'], ['date'], ['st_time']);
        $step_time = Util::getSetting(30,'step_time',true);
        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'step_time' => $step_time,
        ]);
    }

    //管理者用　サロンと日付を指定して1日の予約を取得
    public function getAllBookingsOfSalonAndDate(Request $request)
    {
        Log::info(__METHOD__ . '(start)');
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
        $step_time = Util::getSetting(30,'step_time',true);
        
        $util = new Util();
        
        $times = $util->getTimes($st_time,$ed_time,$step_time);
        $timesNum = $util->getTimesNum($st_time,$ed_time,$step_time);
        
        Log::info(__METHOD__ . '(end)');
        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'checkdate' => $date,
            'selectedSalon' => $salon,
            'salons' => $salons,
            'times' => $times,
            'timesNums' => $timesNum,
            'courses' => $courses,
            'step_time' => $step_time,
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
        Log::info(__METHOD__ . '(start)');
        $booking = new Booking();
        
        $date = $request->date;
        $st_hour = $request->st_hour;
        $st_minute = $request->st_minute;
        $ed_hour = $request->ed_hour;
        $ed_minute = $request->ed_minute;
        $ed_hour_for_show = $request->ed_hour_for_show;
        $ed_minute_for_show = $request->ed_minute_for_show;
        $pet_id = $request->pet;
        $course_id = $request->course;
        $price =  $request->price;
        $salon_id = $request->salon;
        $booking_status = 1;
        
        $st_time = $st_hour * 60 + $st_minute;
        $ed_time = $ed_hour * 60 + $ed_minute;
        $ed_time_for_show = $ed_hour_for_show * 60 + $ed_minute_for_show;
        
        
        $booking->date = $date;
        $booking->st_time = $st_time;
        $booking->ed_time = $ed_time;
        $booking->ed_time_for_show = $ed_time_for_show;
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
        Log::info(__METHOD__ . '(end)');
            return redirect('/admin/makebooking')->with("success", "予約を登録しました");
        }
        
        //削除する予約の確認画面
        public function deleteConfirmForStaff($bookingID)
        {   Log::info(__METHOD__ . '(start)');
            $booking = Booking::find($bookingID);
            
            if (!is_null($booking)) {
                $booking_owner_id = $booking->pet->user->id;
            }
            
            if ((is_null($booking))) {
                return redirect('/bookings')
                ->with('error', '該当の予約が存在しません。');
            }
            
            Log::info(__METHOD__ . '(end)');
            return view('admin.bookings.cancelConfirm', [
                'booking' => $booking
            ]);
        }

        //管理者用　空き枠の取得
        public function getAcceptableCount()
        {
            Log::info(__METHOD__ . '(start)');
            $acceptableCount = [];
            $st_date = date('Y-m-d');
            $salons = Salon::all();
            $salon = $salons->find(1);
            
            
            $ed_date = Util::addDays($st_date, 7);
            $step_time = Util::getSetting(30,'step_time',true);
            
            $allBookings = Booking::all();
            $allDefaultCapacities = DefaultCapacity::all();
            $allRegularHolidays = RegularHoliday::all();
            $allTempCapacities = TempCapacity::all();
            
            $util = new Util();
            $st_time = $salon->st_time;
            $ed_time = $salon->ed_time;
            $times = $util->getTimes($st_time,$ed_time,$step_time);
            $timesNum = $util->getTimesNum($st_time,$ed_time,$step_time);
            $days = $util -> getDaysList($st_date,$ed_date);
            
            $bookingsCalc = new BookingsCalc();
            
            $capacities =
            $bookingsCalc->getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date);
            #Log::debug($capacities);
            
            Log::info(__METHOD__ . '(end)');
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
        Log::info(__METHOD__ . '(start)');
        $acceptableCount = [];
        $st_date = $request->st_date;
        $salons = Salon::all();
        $salon = $salons->find($request->salon);
        
        $ed_date = Util::addDays($st_date, 7);
        $step_time = Util::getSetting(30,'step_time',true);
        
        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        $allRegularHolidays = RegularHoliday::all();
        
        $util = new Util();
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        $times = $util->getTimes($st_time,$ed_time,$step_time);
        $timesNum = $util->getTimesNum($st_time,$ed_time,$step_time);
        $days = $util -> getDaysList($st_date,$ed_date);
        
        $bookingCalcs = new BookingsCalc;
        
        $capacities =
        $bookingCalcs -> getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date);
        
        Log::info(__METHOD__ . '(end)');
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


    public function adminShowBookingDetail($bookingId){
        Log::info(__METHOD__ . '(start)');

        $booking = Booking::with('pet') ->find($bookingId);

        Log::info(__METHOD__ . '(end)');
        return view('admin.bookings.showBookingDetail', [
            'booking' => $booking
        ]);
    }

    public function selectCalenderSalonAndDate(Request $request, $salon, $st_date)
    {
        Log::info(__METHOD__ . '(start)');
        $owner = Auth::user();
        $pet =  session('pet');
        $course = session('course');
        $salon = session('salon');
        
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        
        $step_time = Util::getSetting(30,'step_time',true);
        
        //指定日より1週間分のデータを取得
        $ed_date =  Util::addDays($st_date, 6);
        
        $util = new Util();
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        $times = $util->getTimes($st_time,$ed_time,$step_time);
        $timesNum = $util->getTimesNum($st_time,$ed_time,$step_time);
        $days = $util -> getDaysList($st_date,$ed_date);
        
        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        $allRegularHoliday = RegularHoliday::all();
        
        $bookingsCalc = new BookingsCalc();
        
        $capacities =
        $bookingsCalc->getCanBookList($allBookings, $allDefaultCapacities, $allRegularHoliday, $allTempCapacities, $salon, $step_time, $st_date, $ed_date, $course);
        #Log::debug($capacities);
        
        session([
            'course' => $course,
            'salon' => $salon,
        ]);
        
        $beforeDate = Util::addDays($st_date, -7);
        $afterDate = Util::addDays($st_date, 7);
        
        Log::info(__METHOD__ . '(end)');
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
        Log::info(__METHOD__ . '(start)');
        $booking = new Booking();
        $owner = Auth::user();
        
        $date = session('date');
        $st_time = session('time');
        $cut_time = session('course')->minute;
        $cut_time_for_show = session('course')->minute_for_show;
        $ed_time = $st_time + $cut_time;
        $ed_time_for_show = $st_time + $cut_time_for_show;
        $pet_id = session('pet')->id;
        $course_id = session('course')->id;
        $price =  session('course')->price;
        $salon_id = session('salon')->id;
        $booking_status = 1;
        $message = session('message');
        
        Log::debug(__METHOD__ . ' message：' . $message);
        
        $booking->date = $date;
        $booking->st_time = $st_time;
        $booking->ed_time = $ed_time;
        $booking->ed_time_for_show = $ed_time_for_show;
        $booking->pet_id = $pet_id;
        $booking->course_id = $course_id;
        $booking->price = $price;
        $booking->booking_status = $booking_status;
        $booking->salon_id = $salon_id;
        $booking->message = $message;
        
        $booking->save();
        Log::info(__FUNCTION__ . ' 予約登録：(pet_id)' . session('pet')->id . ' (course)' . session('course')->id . '(date)' . session('date')) . '(st_time)' . $st_time . '(ed_time)' . $ed_time . ('booking_status') . $booking_status;
        
        Log::info(__METHOD__ . ' start save default message of pet id('.$pet_id .') -> "' .$message);
        
        $pet = Pet::find($pet_id);
        $pet->message = $message;
        $pet -> save();
        Log::info(__METHOD__ . ' end save default message of pet id('.$pet_id .') -> "' .$message);

        Mail::to($owner->email)
        ->send(new ContactAdminMail());
        
        
        Mail::to(session('salon')->email)
        ->send(new BookingNotificationForSalon());
        
        
        Log::info(__METHOD__ . '(end)');
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

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Salon;
use App\Models\User;
use App\Models\DefaultCapacity;
use App\Models\RegularHoliday;
use App\Models\TempCapacity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\classes\Util;
use App\Models\CourseMaster;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAdminMail;
use App\Mail\BookingNotificationForSalon;
use App\Mail\CancelMailToUser;
use App\classes\BookingsCalc;
use App\Mail\CancelNotificationToUser;
use App\Mail\NonMemberCancelNotificationToNonmember;
use App\Models\NonMemberBooking;
use App\Http\Controllers\OpenCloseSalonController;
use Exception;

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
     *   ユーザー用
     *
     ***************************************************************/

    //ログイン中のユーザーの予約一覧を表示
    public function index()
    {
        $owner = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $owner->id . ')');

        //BookingController::bookingCheck();

        $showBookingsAfterNDays = Util::getSetting(30, 'showBookingsAfterNDays', true);
        Log::debug(__METHOD__.'('.__LINE__.')'.'$showBookingsAfterNDays:' . Util::addDays(date('Y-m-d'), $showBookingsAfterNDays));
        
        $booking_list_st_date = Util::addDays(date('Y-m-d'), -$showBookingsAfterNDays);
        Log::debug(__METHOD__.'('.__LINE__.')'.'getting booking list, with $booking_list_st_date:' . $booking_list_st_date);
        $bookings = Booking::query()
            ->with('pet.user')
            ->with('course.coursemaster')
            ->with('pet.dogtype')
            ->where('date', '>=', $booking_list_st_date)
            ->where('pet_id', '>', 0)
            ->orderBy('date')
            ->orderBy('st_time');
        
        // SQLの中身を確認
        Log::debug(__METHOD__.'('.__LINE__.')'.'SQL:' . $bookings->toSql());
        Log::debug($bookings->getBindings());

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "ユーザーによる自身の予約一覧の表示[{$method_name}]";
        $check_log_detail =  "{$booking_list_st_date}より後の予約データを取得。";
        $request_from_user = request();
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);
        

        $bookings = $bookings->get();

        // 取得した予約データを確認
        Log::debug(__METHOD__.'('.__LINE__.')'.'$bookings:');
        Log::debug($bookings);

        $count_salons = Util::getCountSalons();
        Log::debug(__METHOD__ . '(' . __LINE__ . ') salons_count:' . $count_salons);

        Log::info(__METHOD__ . ' ends by user_id(' . $owner->id . ')');

        $view = 'bookings.index';

        $params = [
            'owner' => $owner,
            'bookings' => $bookings,
            'showBookingsAfterNDays' => $showBookingsAfterNDays,
            'count_salons' => $count_salons,
        ];

        return view($view,$params);
    }

    //予約するペットを選択する
    public function selectPet()
    {
        $owner = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $owner->id . ')');
        $owner = Auth::user();
        session([
            'owner' => $owner,
        ]);

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "[自動]予約のために自身のペット一覧を自動取得[{$method_name}]";
        $check_log_detail = "";
        $request_from_user = request();
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);

        $pets = Pet::where('owner_id', $owner->id)->get();
        session(['pets' => $pets]);
        $countOfPets = $pets -> count();
        Log::debug(__METHOD__.'('.__LINE__.') #$countOfPets(' . $countOfPets .')');

        $view = 'bookings.selectpet';

        $params = [
            'pets' => $pets,
            'owner' => $owner,
            'countOfPets' => $countOfPets,
        ];

        Log::info(__METHOD__ . ' ends by user_id(' . $owner->id . ')');
        return view($view,$params);
    }

    //コースを選択する
    public function selectCourse(Request $request)
    {
        $owner = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $owner->id . ')');
        $pets = session('pets');
        $pet_id = $request->pet;

        // ペットの情報を再度習得
        $pet = Pet::query()
        ->find($pet_id);

        Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') pet_id:' . $pet_id);
        // Log::debug(__METHOD__.'('.__LINE__.')pets');
        // Log::debug($pets);

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "予約のためにサロンの一覧を取得[{$method_name}]";
        $check_log_detail = "";
        $request_from_user = request();
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);
        $salons = Salon::all();

        if(is_null($pet)){
            Log::error(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') : pet null error !!');
            return redirect()->route('user.newBooking')
            ->with("error", "ペット情報の読込に失敗しました。お手数ですが最初から予約をやり直してください。");
        } else{
            // 操作記録をDBに
            $user =$owner;
            $method_name = __METHOD__;
            $realIp = request()->ip();

            $user_info = "user_id({$user->id}) IP[{$realIp}]";
            $check_log_summary = "[自動]ユーザーによるコース情報の取得。[{$method_name}]";
            $check_log_detail = "dog_type:{$pet->dogtype->type} のコース情報を取得";
            $request_from_user = request();
            $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);
            $courses = Course::where('dogtype_id', $pet->dogtype_id)->get();
        }
        $message_before = $pet->message;

        Log::debug(__METHOD__ . '( before message is "' . $message_before . '"');

        session([
            'pet' => $pet,
            'courses' => $courses,
            'salons' => $salons,
        ]);
        
        $view = 'bookings.selectcourse';
        $messages = Util::getNotifications($view);
        $params = [
            'owner' => $owner,
            'pet' => $pet,
            'courses' => $courses,
            'salons' => $salons,
            'message_before' => $message_before,
        ];

        Log::info(__METHOD__ . ' ends by user_id(' . $owner->id . ')');
        return view($view,$params);
    }

    public function selectCalender(Request $request)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        $salons = session('salons');
        $salon = $salons->find($request->salon);
        
        $util = new Util();
        $today = date('Y-m-d');
        $ed_date =  $util->addDays($today, 6);

        $pet =  session('pet');
        
        $courses = session('courses');
        $course = $courses->find($request->course);
        $needed_time = $course->minute;
        
        $view_for_show = $this->getCalenderView($salon,$today,$ed_date,$pet,$needed_time,$course);
        $message = $request->message;

        session([
            'course' => $course,
            'salon' => $salon,
            'message' => $message,
        ]);

        Log::debug(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return $view_for_show;


        $owner = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $owner->id . ')');
        $pet =  session('pet');
        $courses = session('courses');
        $course = $courses->find($request->course);
        session(['course' => $course]);
        $salons = session('salons');
        $salon = $salons->find($request->salon);
        session(['salon' => $salon]);
        $st_time = $salon->st_time;

        $message = $request->message;
        session('message', $message);

        $step_time = Util::getSetting(30, 'step_time', true);
        session('step_time', $step_time);
        $util = new Util();

        //初期値は本日より1週間分のデータを取得
        $st_date = date('Y-m-d');
        $ed_date =  $util->addDays($st_date, 6);

        $course_master_id = $course->courseMaster->id;

        // コースごとの終了時間を習得
        if ($course_master_id == 1) {
            Log::debug(__METHOD__.'('.__LINE__.')'.'env("FINAL_BOOKING_TIME_COURSE1",15)'. env("FINAL_BOOKING_TIME_COURSE1"));
            $ed_time = 60 * env("FINAL_BOOKING_TIME_COURSE1",17) + 1;
        } else {
            Log::debug(__METHOD__.'('.__LINE__.')'.'env("FINAL_BOOKING_TIME_COURSE2",15)'. env("FINAL_BOOKING_TIME_COURSE2"));
            $ed_time = 60 * env("FINAL_BOOKING_TIME_COURSE2",16) + 1;
        }


        // カレンダーの生成に必要な各種データを習得
        $times = $util->getTimes($st_time, $ed_time, $step_time);
        $timesNum = $util->getTimesNum($st_time, $ed_time, $step_time);
        $timesCount = $util->getTimesCount($st_time, $ed_time, $step_time);
        $days = $util->getDaysList($st_date, $ed_date);

        $bookingsCalc = new BookingsCalc();
        $salon_id = $salon->id;
        $needed_time = $course->minute;
        Log::debug(__METHOD__ . '(' . __LINE__ . ') salon_id:' . $salon_id . ' $needed_time='. $needed_time);

        //2022_1016_2043 テスト
        $openCloseSalonController = new OpenCloseSalonController();
        $course_master_id = $course->courseMaster->id;
        Log::debug(__METHOD__ . '(' . __LINE__ . ') course_master_id: ' . $course_master_id);

        $salon_id = $salon->id;

        Log::info(__METHOD__.'('.__LINE__.')'.'start_to_calc_capacities');
        
        $capacities = $bookingsCalc->getCanBookList3($salon,$st_date,$ed_date,$needed_time,$course);

        session([
            'course' => $course,
            'salon' => $salon,
            'message' => $message,
        ]);

        $beforeDate = Util::addDays($st_date, -7);
        $afterDate = Util::addDays($st_date, 7);
        $today = date('Y-m-d');
        $maxBookingDate = Util::getEndOfTheMonth($today, 2);
        $timesCount = $util->getTimesCount($st_time, $ed_time, $step_time);

        $view = 'bookings.booking_calender';
        $messages = Util::getNotifications( $view);

        $params = "";
        $params =[
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
            'today' => $today,
            'maxBookingDate' => $maxBookingDate,
            'ed_date' => $ed_date,
            'timesCount' =>$timesCount,
            'timeOfFirst' => $times[$st_time],
        ];

        Log::info(__METHOD__ . ' ends by user_id(' . $owner->id . ')');

        return view($view,$params);
    }

    public function confirmBooking(Request $request, $date, $time)
    {

        $owner = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $owner->id . ')');
        $pet =  session('pet');
        $course =  session('course');
        $message =  session('message');
        $date = $request->date;
        $time = $request->time;

        $today = date('Y-m-d');
        $maxBookingDate = Util::getEndOfTheMonth($today, 2);

        //予約できるかチェック
        if($date<$today){
            Log::warning(__METHOD__.'('.__LINE__.') error $date<$today by user_id(' . $owner->id . ')');
            return '無効な日付です。';
        }
        if($date>$maxBookingDate){
            Log::warning(__METHOD__.'('.__LINE__.') error $date>$maxBookingDate by user_id(' . $owner->id . ')');
            return '無効な日付です。';
        }

        $timeStr = Util::minuteToTime($time);
        session([
            'date' => $date,
            'time' => $time,
        ]);

        $view = 'bookings.confirm';
        $messages = Util::getNotifications($view);

        $params =[
            'owner' => $owner,
            'pet' => $pet,
            'course' => $course,
            'date' => $date,
            'time' => $time,
            'timeStr' => $timeStr,
            'message' => $message,
        ];

        Log::info(__METHOD__ . ' ends by user_id(' . $owner->id . ')');
        return Util::getViewWithNotifications($view,$params);
    }

    //削除する予約の確認画面
    public function deleteConfirm($bookingID)
    {
        $owner = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $owner->id . ')');

        $booking = Booking::find($bookingID);

        $owner_id = $owner->id;
        if (!is_null($booking) and !is_null($booking->pet)) {
            $booking_owner_id = $booking->pet->user->id;
        } else{
            Log::warning(__METHOD__.'('.__LINE__.') owner(' .$owner->id . ') tried to access null booking or non Member booking id(' .$bookingID.')');
            return redirect('/bookings')
            ->with('error', '該当の予約が存在しません。');
        }
        
        if ((is_null($booking) or ($owner_id != $booking_owner_id))) {
            Log::warning(__METHOD__.'('.__LINE__.') owner(' .$owner->id . ') tried to access other owner"s booking id(' .$bookingID.')');
            return redirect('/bookings')
                ->with('error', '該当の予約が存在しません。');
        }
        

        if($booking -> date <= date('Y-m-d')){
            Log::warning(__METHOD__.'('.__LINE__.') owner(' .$owner->id . ') tried to access booking that is not able to be canceled! Booking id was ' . $bookingID .'!');
            return redirect('/bookings')
            ->with('error', 'キャンセルできない予約です。。');
        }

        $view = 'bookings.cancelConfirm';
        $messages = Util::getNotifications($view);

        $params = [
            'booking' => $booking,
        ];

        Log::info(__METHOD__ . ' ends by user_id(' . $owner->id . ')');
        return Util::getViewWithNotifications($view,$params);
    }

    //予約のキャンセル処理
    public function destroy($id)
    {
        $owner = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $owner->id . ')');
        $booking = Booking::findOrFail($id);

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "ユーザーによる予約キャンセル[{$method_name}]";
        $check_log_detail = $booking;
        $request_from_user = request();
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);

        //キャンセルのメールを送りたい
        $booking->delete();
        Log::notice(__METHOD__ . ' owner user_id(' . $owner->id . ') deleted booking id(' . $booking->id . ')');
        Log::info(' deleted booking:' . $booking);

        session([
            'booking' => $booking,
        ]);

        $salon = Salon::find($booking->salon_id);

        Mail::to($owner->email)
            ->send(new CancelMailToUser());
        Log::debug(__METHOD__ . 'system sent a message to user(' . $owner->id . ') whose mail address ="' .  $owner->email . '"');

        Mail::to($salon->email)
            ->send(new CancelMailToUser());
        Log::debug(__METHOD__ . 'system sent a message to salon (' . $salon->id . ') whose mail address ="' .  $salon->email . '"');


        $user = Auth::user();
        Log::debug('User ' . $user->id . 'canceled booking_id=' . $id . ' ' . $booking->getBookingInfo());


        Log::info(__METHOD__ . ' ends by user_id(' . $owner->id . ')');
        return redirect('/bookings')
            ->with('success', '予約をキャンセルしました。');
    }



    /**************************************************************
     *
     *   管理者用
     *
     ***************************************************************/

    public function adminDeleteBookingConfirm(Request $request, $bookingID)
    {

        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $staff->id . ')');
        $booking = Booking::find($bookingID);

        Log::info(__METHOD__ . ' ends by user_id(' . $staff->id . ')');
        return view('admin.bookings.deleteConfirm', [
            'booking' => $booking
        ]);
    }

    public function adminDeleteBooking(Request $request, $bookingID)
    {
        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by staff user_id(' . $staff->id . ')');
        $booking = Booking::find($bookingID);
        Log::debug($booking);

        $booking->delete();
        Log::notice(__METHOD__ . ' staff user_id(' . $staff->id . ') deleted booking id(' . $booking->id . ')');
        Log::debug(' deleted booking:' . $booking);
        Log::debug(__METHOD__ . ' booking deleted ID:' . $bookingID . ' by user ID(' . $staff->id . ')');

        session([
            'booking' => $booking,
        ]);

        $salon = Salon::find($booking->salon_id);

        //非会員の予約の場合、そちらも削除が必要。
        if ($booking->pet_id == 0) {
            Log::debug(__METHOD__ . ': This booking is by non member. Need to delete non member booking!');
            $nonMemberBooking = NonMemberBooking::where('booking_id', $bookingID)->first();

            session([
                'nonMemberBooking' => $nonMemberBooking,
            ]);

            $email = $nonMemberBooking->email;
            Log::debug(__METHOD__ . ' Deleting non member booking:' . $nonMemberBooking);
            $nonMemberBooking->delete();
            Log::notice(__METHOD__ . ' staff user_id(' . $staff->id . ') deleted nonMember Booking id(' . $nonMemberBooking->id . ')');
            Log::debug(' deleted nonMemberBooking:' . $nonMemberBooking);
            Log::debug(__METHOD__ . ' non user email:' . $email);

            Mail::to($salon->email)
                ->send(new NonMemberCancelNotificationToNonmember);
            Mail::to($email)
                ->send(new NonMemberCancelNotificationToNonmember);
        } else {
            $owner = User::find($booking->pet->user->id);
            $email = $owner->email;
            session(['owner' => $owner]);
            Log::debug(__METHOD__ . ' delete only a booking because it has user:');
            Log::debug(__METHOD__ . ' user email:' . $email);
            Mail::to($email)
                ->send(new CancelNotificationToUser);
            Log::debug(__METHOD__ . 'system sent a message to user(' . $owner->id . ') whose mail address ="' .  $owner->email . '"');

            Mail::to($salon->email)
                ->send(new CancelNotificationToUser);
            Log::debug(__METHOD__ . 'system sent a message to user(' . $owner->id . ') whose mail address ="' .  $owner->email . '"');
        }



        Log::info(__METHOD__ . ' ends by user_id(' . $staff->id . ')');
        return redirect()->route('admin.checkBookings.dateAndSalon')
            ->with("success", "予約をキャンセルしました");
    }


    public function getTodayAllBookings()
    {
        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $staff->id . ')');
        $bookings = Booking::orderBy('salon_id')
            ->orderBy('date')
            ->orderBy('st_time')
            ->get();
        session(['bookings' => $bookings]);

        $today = date('Y-m-d');
        $default_salon = $staff->default_salon;

        $bookings = $bookings
            ->sortBy(['salon_id'], ['date'], ['st_time']);
        $step_time = Util::getSetting(30, 'step_time', true);
        Log::info(__METHOD__ . ' ends by user_id(' . $staff->id . ')');
        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'step_time' => $step_time,
            'checkdate'=>$today,
            'default_salon'=>$default_salon,
        ]);
    }

    //管理者用　サロンと日付を指定して1日の予約を取得
    public function getAllBookingsOfSalonAndDate(Request $request)
    {
        $staff = Auth::user();
        Log::info(__METHOD__.'('.__LINE__. ') starts by user_id(' . $staff->id . ')');
        $salons = Salon::all();
        $courses = CourseMaster::all();

        Log::debug(__METHOD__.'('.__LINE__.') $staff'.$staff);

        if ($request->has('salon')) {
            $salon = $salons->find($request->salon);
            Log::debug(__METHOD__.'('.__LINE__.') $request has salon(' . $request->salon .')' );
        } else {
            $salon = $salons->find($staff->default_salon);
            Log::debug(__METHOD__.'('.__LINE__.') $request does not have salon so put default salon('.$staff->default_salon.')');
        }

        if ($request->has('date')) {
            $date = $request->date;
        } else {
            $date = date('Y-m-d');
        }

        
        $bookings = Booking::where('date', $date)
            ->where('salon_id', $salon->id)
            ->orderBy('st_time')
            ->get();

        Log::debug(__METHOD__.'('.__LINE__. ') salon_id:' . $salon->id . ' date:' . $date);

        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        $step_time = Util::getSetting(30, 'step_time', true);

        $util = new Util();

        $times = $util->getTimes($st_time, $ed_time, $step_time);
        $timesNum = $util->getTimesNum($st_time, $ed_time, $step_time);

        Log::debug(__METHOD__.'('.__LINE__.') sraff(' . $staff->id .') is getting all Bookings info before ' . $date);
        $usersCameBeforeList = Util::getWhoCameBefore($date,$salon->id);

        Log::info(__METHOD__.'('.__LINE__. ') ends by user_id(' . $staff->id . ')');
        Log::debug(__METHOD__.'('.__LINE__. ') $salon:' . $salon);
        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'checkdate' => $util->getYMDWFromDbDate($date),
            'selectedSalon' => $salon,
            'salons' => $salons,
            'times' => $times,
            'timesNums' => $timesNum,
            'courses' => $courses,
            'step_time' => $step_time,
            'usersCameBeforeList' => $usersCameBeforeList,
            'date' => $date,
        ]);
    }

    //管理者が予約を作成する画面
    public function adminMakeBooking()
    {
        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $staff->id . ')');
        $pets = Pet::all();
        $salons = Salon::all();
        $courses = Course::all();

        Log::info(__METHOD__ . ' ends by user_id(' . $staff->id . ')');
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
        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $staff->id . ')');
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

        // 操作記録をDBに
        $user =Auth::user();
        $realIp = request()->ip();
 
        $user_info = "user_id({$user->id}) IP[{$realIp}]";

        try{
            $util = new Util();
            $access_log_st_time = $util->minuteNumToTime($st_time);
            $access_log_ed_time = $util->minuteNumToTime($ed_time);
            $access_log_detail = "{$date} - {$access_log_st_time} から{$access_log_ed_time} までの予約の保存";
            $util::recordAccessLog(__METHOD__,$user_info ,"[スタッフ]予約に伴う予約の登録",$access_log_detail,$request);
            $booking->save();
            Log::notice(__METHOD__ . ' staff:user_id(' . $staff->id . ') saved booking. Booking ID is (' . $booking->id . ')');
    
            //○×表を閉じる
            Log::info(__METHOD__ . '(' . __LINE__ . ') get course master to close OX by staff(' . $staff . ')');
            $course_master = CourseMaster::find($course_id);
            $util = new Util();
            $access_log_detail = "{$date} - {$access_log_st_time} から{$access_log_ed_time} までの予約にともなう◯×の更新:";
            $access_log_id = $util::recordAccessLog(__METHOD__,'user_id;' . $util->getUserId(),"[スタッフ]予約に伴う◯×の更新",$access_log_detail,$request);
            $util->closeBooked($salon_id, $date, $st_time, $ed_time, $course_master->course_master_id,$access_log_id);
    
    
            Log::debug('管理者予約登録：(pet_id)' . $pet_id .
                ' (course)' . $course_id .
                '(date)' . $date  .
                '(st_time)' . $st_time .
                '(ed_time)' . $ed_time .
                ('booking_status') . $booking_status);
    
            #Log::debug('ここでメールを送りたい。');
            Mail::to('kim.ksuke@gmail.com')
                ->send(new ContactAdminMail());


        }catch(Exception $e){
            Log::error($e);
            return 'エラーが発生しました。お手数ですが直接店舗にお電話ください。';
        }    
        Log::info(__METHOD__ . ' ends by user_id(' . $staff->id . ')');
        return redirect('/admin/makebooking')->with("success", "予約を登録しました");
    }

    //削除する予約の確認画面
    public function deleteConfirmForStaff($bookingID)
    {
        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by staff user_id(' . $staff->id . ')');
        $booking = Booking::find($bookingID);

        if (!is_null($booking)) {
            $booking_owner_id = $booking->pet->user->id;
        }

        if($booking->pet->id ==0){
            Log::warning(__METHOD__.'('.__LINE__.') staff(' . $staff->id .') tried to open a booking info by illegal way!! ');
            return redirect('/bookings')
            ->with('error', '該当の予約が存在しません。');
        }
        
        if ((is_null($booking))) {
            Log::warning(__METHOD__.'('.__LINE__.') staff(' . $staff->id .') tried to open a booking info by illegal way!! ');
            return redirect('/bookings')
                ->with('error', '該当の予約が存在しません。');
        }


        Log::info(__METHOD__ . ' ends by staff user_id(' . $staff->id . ')');
        return view('admin.bookings.cancelConfirm', [
            'booking' => $booking
        ]);
    }

    //管理者用　空き枠の取得
    public function getAcceptableCount()
    {
        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by staff user_id(' . $staff->id . ')');
        $acceptableCount = [];
        $st_date = date('Y-m-d');
        $salons = Salon::all();
        $salon_id = $staff->default_salon;
        $salon = $salons->find($salon_id);


        $ed_date = Util::addDays($st_date, 6);
        $step_time = Util::getSetting(30, 'step_time', true);

        // $allBookings = Booking::all();
        // $allDefaultCapacities = DefaultCapacity::all();
        // $allRegularHolidays = RegularHoliday::all();
        // $allTempCapacities = TempCapacity::all();

        $util = new Util();
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        $times = $util->getTimes($st_time, $ed_time, $step_time);
        $timesNum = $util->getTimesNum($st_time, $ed_time, $step_time);
        $days = $util->getDaysList($st_date, $ed_date);

        $bookingsCalc = new BookingsCalc();

        $capacities = $this->test($salon_id, $st_date);
        Log::debug(__METHOD__ . '(' . __LINE__ . ')' . ' old default capacities:');
        Log::debug($capacities);


        Log::info(__METHOD__ . ' ends by staff user_id(' . $staff->id . ')');
        #return __METHOD__;
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

    public function showNonMember($bookingId)
    {
        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by staff user_id(' . $staff->id . ')');
        $nonMemberBooking = NonMemberBooking::where('booking_id', $bookingId)->first();
        Log::debug($nonMemberBooking);
        Log::info(__METHOD__ . ' ends by staff user_id(' . $staff->id . ')');

        return view('admin.users.showNonMember', [
            'nonMemberBooking' => $nonMemberBooking,
        ]);
        return __METHOD__;
    }

    public function getAcceptableCountWithSalonDate(Request $request)
    {
        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by staff user_id(' . $staff->id . ')');
        $acceptableCount = [];
        $st_date = $request->st_date;
        $salons = Salon::all();
        $salon = $salons->find($request->salon);

        $ed_date = Util::addDays($st_date, 6);
        $step_time = Util::getSetting(30, 'step_time', true);

        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        $allRegularHolidays = RegularHoliday::all();

        $util = new Util();
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        $times = $util->getTimes($st_time, $ed_time, $step_time);
        $timesNum = $util->getTimesNum($st_time, $ed_time, $step_time);
        $days = $util->getDaysList($st_date, $ed_date);

        $bookingCalcs = new BookingsCalc;

        $capacities =
            $bookingCalcs->getOtherCapacitiesOfMultiDate($allBookings, $allDefaultCapacities, $allRegularHolidays, $allTempCapacities, $salon, $step_time, $st_date, $ed_date);


        Log::info(__METHOD__ . ' ends by staff user_id(' . $staff->id . ')');
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


    public function adminShowBookingDetail($bookingId)
    {
        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by staff user_id(' . $staff->id . ')');

        $booking = Booking::with('pet')->find($bookingId);


        Log::info(__METHOD__ . ' ends by user_id(' . $staff->id . ')');
        return view('admin.bookings.showBookingDetail', [
            'booking' => $booking
        ]);
    }

    public function selectCalenderSalonAndDate(Request $request, $salon_id, $st_date)
    {
        $owner = Auth::user();
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' starts by owner user_id(' . $owner->id . ')');
        

        $today = date('Y-m-d');
        $maxBookingDate = Util::getEndOfTheMonth($today, 2);
        $salon = session('salon');

        if($st_date<$today){
            return redirect(Route('booking.selectCalender.salonAndDay',['salon'=>$salon,'st_date'=>$today]))
            ->with('error','無効な日付です。');
        }
        if($st_date>$maxBookingDate){
            return redirect(Route('booking.selectCalender.salonAndDay',['salon'=>$salon,'st_date'=>$today]))
            ->with('error','無効な日付です。');
        }

        $util = new Util();
        $ed_date =  $util->addDays($st_date, 6);

        $pet = session('pet');

        $courses = session('courses');
        $course = session('course');
        $needed_time = $course->minute;


        $view_for_show = $this->getCalenderView($salon,$st_date,$ed_date,$pet,$needed_time,$course);
        return $view_for_show;




        $pet =  session('pet');
        $course = session('course');
        $salon = session('salon');

        if(is_null($salon)){
            Log::error(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') Error sallon is null !!');
            
        }

        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        $message = session('message');

        $step_time = session('step_time');

        //指定日より1週間分のデータを取得
        $ed_date =  Util::addDays($st_date, 6);
        Log::debug(__METHOD__ . '(' . __LINE__ . ')');
        $util = new Util();
        $st_time = $salon->st_time;

        $course_master_id = $course->courseMaster->id;
        if ($course_master_id == 1) {
            $ed_time = 60 * 17 + 1;
        } else {
            $ed_time = 60 * 16 + 1;
        }

        if ($course_master_id == 1) {
            Log::debug(__METHOD__.'('.__LINE__.')'.'env("FINAL_BOOKING_TIME_COURSE1",15)'. env("FINAL_BOOKING_TIME_COURSE1"));
            $ed_time = 60 * env("FINAL_BOOKING_TIME_COURSE1",17) + 1;
        } else {
            Log::debug(__METHOD__.'('.__LINE__.')'.'env("FINAL_BOOKING_TIME_COURSE2",15)'. env("FINAL_BOOKING_TIME_COURSE2"));
            $ed_time = 60 * env("FINAL_BOOKING_TIME_COURSE2",16) + 1;
        }

        $step_time = Util::getSetting(30, 'step_time', true);
        Log::debug(__METHOD__ . '(' . __LINE__ . ') st_time:' . $st_time . ' ed_time:' . $ed_time . ' step_time:' . session('step_time'));

        $times = $util->getTimes($st_time, $ed_time, $step_time);
        Log::debug(__METHOD__.'('.__LINE__.')'.'times:');
        Log::debug($times);
        Log::debug(__METHOD__ . '(' . __LINE__ . ')');
        $timesNum = $util->getTimesNum($st_time, $ed_time, $step_time);
        Log::debug(__METHOD__ . '(' . __LINE__ . ')');
        $days = $util->getDaysList($st_date, $ed_date);
        Log::debug(__METHOD__ . '(' . __LINE__ . ')');
        $bookingCalc = new BookingsCalc();
        $salon_id = $salon->id;
        $course_master_id = $course->courseMaster->id;
        $needed_time = $course->minute;

        $bookingsCalc = new BookingsCalc();
        Log::debug(__METHOD__ . '(' . __LINE__ . ')');

        $openCloseSalonController = new OpenCloseSalonController();

        $bookingsCalc = new BookingsCalc();
        Log::info(__METHOD__.'('.__LINE__.')'.'call bookingsCalc->getCanBookList3');
        $capacities = $bookingsCalc->getCanBookList3($salon,$st_date,$ed_date,$needed_time,$course);

        session([
            'course' => $course,
            'salon' => $salon,
        ]);

        $beforeDate = Util::addDays($st_date, -7);
        $afterDate = Util::addDays($st_date, 7);

        $timesCount = $util->getTimesCount($st_time, $ed_time, $step_time);

        if ($ed_date > $maxBookingDate) {
            $ed_date = $maxBookingDate;
        }

        Log::info(__METHOD__ . ' ends by owner user_id(' . $owner->id . ')');
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
            'today' => $today,
            'ed_date' => $ed_date,
            'maxBookingDate' => $maxBookingDate,
            'timesCount' =>$timesCount,
            'timeOfFirst' => $times[$st_time]
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
        $staff = Auth::user();
        Log::info(__METHOD__ . ' starts by owner user_id(' . $staff->id . ')');
        
        $booking = new Booking();
        $owner = Auth::user();

        $salon = session('salon');
        $course = session('course');

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

        // 現在日付を保存前にチェック
        $today = date('Y-m-d');
        // $today = date('2024-05-25');

        if($date<$today){
            Log::warning(__METHOD__ . '(' . __LINE__ . ')' . '無効な日付で予約が試みられました。');
            return redirect(Route('booking.selectCalender.salonAndDay',['salon'=>$salon,'st_date'=>$today]))
            ->with('error','無効な日付です。');
        }

        $maxBookingDate = Util::getEndOfTheMonth($today, 2);

        if($date>$maxBookingDate){
            Log::warning(__METHOD__ . '(' . __LINE__ . ')' . '無効な日付で予約が試みられました。');
            return redirect(Route('booking.selectCalender.salonAndDay',['salon'=>$salon,'st_date'=>$today]))
            ->with('error','無効な日付です。');
        }

        $now_time = date('H')*60 + date('i');
        // $now_time =19*60;
        
        if($date==$today && $now_time+60>$st_time){
            Log::warning(__METHOD__ . '(' . __LINE__ . ')' . '予約可能時刻を過ぎて登録リクエストがあったのでやり直しました。');
            return redirect(Route('booking.selectCalender.salonAndDay',['salon'=>$salon,'st_date'=>$today]))
            ->with('error','予約可能時刻を過ぎました。');
        }

        //予約可能だが、重複をチェック
        $openCloseSalonController = new OpenCloseSalonController();
        $step_time = Util::getSetting(30, 'step_time', true);
        $openCloseList =
            $openCloseSalonController->makeOpenCloseListFromStdateToEddate($salon_id,  $course->course_master_id, $date, $date, $st_time, $ed_time, $step_time);

        $bookingsCalc = new BookingsCalc();
        $can_book = $bookingsCalc -> check_can_book_the_time($date,$st_time,$course->minute,$openCloseList);
        Log::debug(__METHOD__ . '(' . __LINE__ . ')' . 'can_book;' . $can_book);


        if(!$can_book){
            Log::warning(__METHOD__ . '(' . __LINE__ . ')' . '予約可能時刻を過ぎて登録リクエストがあったのでやり直しました。');
            return redirect(Route('booking.selectCalender.salonAndDay',['salon'=>session('salon'),'st_date'=>$today]))
            ->with('error','他のお客様が先に予約をされました。申し訳ありませんが別の日時をご予約ください。');
        }

        try{
            $util = new Util();

            // 操作記録をDBに
            $user =Auth::user();
            $realIp = request()->ip();
            $user_info = "user_id({$user->id}) IP[{$realIp}]";

            $access_log_st_time = $util->minuteNumToTime($st_time);
            $access_log_ed_time = $util->minuteNumToTime($ed_time);
            $access_log_detail = "{$date} - {$access_log_st_time} から{$access_log_ed_time} までの予約の保存:{$message}}";
            $util::recordAccessLog(__METHOD__,$user_info,"予約に伴う予約の保存",$access_log_detail,$request);
            $booking->save();
            Log::notice(__METHOD__ . '  owner user_id(' . $owner->id . ') saved Booking, id(' . $booking->id . ')');
    
            Log::debug(__FUNCTION__ . ' 予約登録：(pet_id)' . session('pet')->id . ' (course)' . session('course')->id . '(date)' . session('date')) . '(st_time)' . $st_time . '(ed_time)' . $ed_time . ('booking_status') . $booking_status;
            Log::debug(__METHOD__ . ' start save default message of pet id(' . $pet_id . ') -> "' . $message);
    

            //○×表を閉じる
            Log::info(__METHOD__ . '(' . __LINE__ . ') get course master to close OX by user(' . $staff . ')');
            $course_master = Course::find($course_id);
            $access_log_detail = "{$date} - {$access_log_st_time} から{$access_log_ed_time} までの予約にともなう◯×の更新:{$message}}";
            $access_log_id = $util::recordAccessLog(__METHOD__,$user_info,"予約に伴う◯×の更新",$access_log_detail,$request);
            $util->closeBooked($salon_id, $date, $st_time, $ed_time, $course_master->course_master_id,$access_log_id);
    
            $pet = Pet::find($pet_id);
            $pet->message = $message;

            $access_log_detail = "pet:{$pet->id} (owner{$pet->user->id}) の予約時のメッセージ保存。";
            $util::recordAccessLog(__METHOD__,$user_info,"予約時のメッセージの変更",$access_log_detail,$request);
            
            $pet->save();
            Log::notice(__METHOD__ . '  owner user_id(' . $owner->id . ') saved message, pet id(' . $pet->id . ')');
    
            Log::debug(__METHOD__ . ' end save default message of pet id(' . $pet_id . ') -> "' . $message);
    
            Mail::to($owner->email)
                ->send(new ContactAdminMail());
            Log::debug(__METHOD__ . 'system sent a message to user(' . $owner->id . ') whose mail address ="' .  $owner->email . '"');
    
            Mail::to(session('salon')->email)
                ->send(new BookingNotificationForSalon());
            Log::debug(__METHOD__ . 'system sent a message to salon (' . session('salon')->id . ') whose mail address ="' .  session('salon')->email . '"');
        }catch(Exception $e){
            Log::error($e);
            Log::debug(__METHOD__.'('.__LINE__.') catch Error: ' . $e);
            Log::debug(__METHOD__.'('.__LINE__.') because of error, we are deleting booking ' . $e);
            Log::debug(' booking:');
            Log::debug($booking);
            $booking -> delete();
            return 'エラーが発生しました。';
        }

        Log::info(__METHOD__ . ' ends by owner user_id(' . $owner->id . ')');
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


    public function testAdjust(Request $request, $salonId, $date, $time)
    {
        Log::debug(__METHOD__ . '(start)');
        $debugString = __METHOD__ . ' salonID:' . $salonId . ' date:' . $date . ' time:' . $time;
        Log::debug($debugString);

        $salon = Salon::find($salonId);
        Log::debug($salon);
        $bookingsCalc = new BookingsCalc();

        $date = date('Y-m-d');
        $step_time = Util::getSetting(30, 'step_time', true);
        $capacities =
            $bookingsCalc->getCapacitiesOfMultiDaysForOX($salon, $date, $date, $step_time);
        Log::debug(__METHOD__ . '(end)');
        return $debugString;
    }

    /**************************************************************
     *
     *   テスト用
     *
     ***************************************************************/
    private function getCalenderView($salon,$st_date,$ed_date,$pet,$needed_time,$course){
        $owner = Auth::user();
        Log::debug(__METHOD__ . '(' . __LINE__ . ')' . ' start!');

        $view = 'bookings.booking_calender';
        
        $beforeDate = Util::addDays($st_date, -7);
        $afterDate = Util::addDays($st_date, 7);
        
        $bookingsCalc = new BookingsCalc();
        $capacities = $bookingsCalc->getCanBookList3($salon,$st_date,$ed_date,$needed_time,$course);

        // カレンダーの表示開始時間時間
        $st_time = $salon->st_time;

        // コースごとの終了時間を習得
        // シャンプーかシャンプーカットか
        $course_master_id = $course->courseMaster->id;
        
        if ($course_master_id == 1) {
            Log::debug(__METHOD__.'('.__LINE__.')'.'env("FINAL_BOOKING_TIME_COURSE1",15)'. env("FINAL_BOOKING_TIME_COURSE1"));
            $ed_time = 60 * env("FINAL_BOOKING_TIME_COURSE1",17) + 1;
        } else {
            Log::debug(__METHOD__.'('.__LINE__.')'.'env("FINAL_BOOKING_TIME_COURSE2",15)'. env("FINAL_BOOKING_TIME_COURSE2"));
            $ed_time = 60 * env("FINAL_BOOKING_TIME_COURSE2",16) + 1;
        }

        // カレンダーの生成に必要な各種データを習得

        $step_time = Util::getSetting(30, 'step_time', true);
        $util = new Util();
        $times = $util->getTimes($st_time, $ed_time, $step_time);
        $timesNum = $util->getTimesNum($st_time, $ed_time, $step_time);
        $timesCount = $util->getTimesCount($st_time, $ed_time, $step_time);
        $days = $util->getDaysList($st_date, $ed_date);

        $message = "";
        $today = date('Y-m-d');
        $maxBookingDate = Util::getEndOfTheMonth($today, 2);

        $params =[
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
            'today' => $today,
            'maxBookingDate' => $maxBookingDate,
            'ed_date' => $ed_date,
            'timesCount' =>$timesCount,
            'timeOfFirst' => $times[$st_time],
        ];
        Log::debug(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return view($view,$params);
    }
}

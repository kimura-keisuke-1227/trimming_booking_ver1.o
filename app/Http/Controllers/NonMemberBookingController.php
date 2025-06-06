<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\NonMemberBooking;
use Illuminate\Support\Facades\Log;
use App\classes\Util;
use App\classes\BookingsCalc;
use App\Models\Course;
use App\Models\Salon;
use App\Models\DefaultCapacity;
use App\Models\RegularHoliday;
use App\Models\TempCapacity;
use App\Models\Setting;
use App\Models\CourseMaster;
use App\Models\Dogtype;
use Illuminate\Support\Facades\Mail;
use App\Mail\NonMemberBookingMail;
use App\Mail\NonMemberBookingMailToSalon;
use App\Http\Controllers\OpenCloseSalonController;
use Exception;

class NonMemberBookingController extends Controller
{
    //
    public function store(Request $request)
    {
        Log::info(__FUNCTION__ . 'start');

        //予約　pet_id = 0 とする　→ pet_id=0ならば登録なしと判定する

        $booking = new Booking();
        $pet_id = 0;
        $course = session('course');
        $date = session('date');
        $st_time = session('time');
        $salon = session('salon');
        $message = session('message');
        $course_id = $course->id;
        #Log::debug(__FILE__ . __FUNCTION__ . ' course_id:' . $course_id);
        Log::debug(__FILE__ . __FUNCTION__ . ' course:' . $course);
        $price =  $course->price;
        $salon_id = $salon->id;
        $booking_status = 1;

        $ed_time = $st_time + $course->minute;
        $ed_time_fow_show = $st_time + $course->minute_for_show;

        $booking->date = $date;
        $booking->st_time = $st_time;
        $booking->ed_time = $ed_time;
        $booking->ed_time_for_show = $ed_time_fow_show;
        $booking->pet_id = $pet_id;
        $booking->course_id = $course_id;
        $booking->price = $price;
        $booking->booking_status = $booking_status;
        $booking->salon_id = $salon_id;
        $booking->message = $message;
        
        try{
            $booking->save();
            $booking_id = $booking->id;
        } catch(Exception $e){
            Log::warning(__METHOD__.'('.__LINE__.') Exception' . $e .' raised when saving booking with non member!!');
            return 'エラーが発生しました。お手数ですが、店舗に直接お電話ください。';
        }
        Log::debug(__FUNCTION__ . ' $booking:' . $booking);
        Log::notice(__FUNCTION__ . 'Booking is saved for non member booking! booking_id is (' . $booking_id . ')');

        //○×表を閉じる
        Log::info(__METHOD__ . '(' . __LINE__ . ') get course master to close OX by non member!');
        Log::debug(__METHOD__ . '(' . __LINE__ . ') find CourseMaster with course_id(' . $course_id . ') !');
        $course_master = Course::find($course_id);
        Log::debug(__METHOD__ . '(' . __LINE__ . ') course_master:' . $course_master);
        Log::debug(__METHOD__ . '(' . __LINE__ . ') $course_master->id:' . $course_master->course_master_id);
        
        $util = new Util();

        //非会員の予約を登録
        $nonMemberBooking = new NonMemberBooking();

        $nonMemberBooking->booking_id = $booking_id;
        $nonMemberBooking->last_name = session('owner_last_name');
        $nonMemberBooking->last_name_kana = session('owner_last_name_kana');
        $nonMemberBooking->first_name = session('owner_first_name');
        $nonMemberBooking->first_name_kana = session('owner_first_name_kana');
        $nonMemberBooking->email = session('mail');
        $nonMemberBooking->phone = session('phone');
        $nonMemberBooking->name = session('pet_name');
        $nonMemberBooking->weight = session('weight');
        try{
            $nonMemberBooking->save();
        }catch(Exception $e){
            Log::warning(__METHOD__.'('.__LINE__.') Exception' . $e .' raised when saving non member booking info with non member!!');
            Log::debug(__METHOD__.'('.__LINE__.')');
            $booking = Booking::find($booking_id);
            $booking -> delete();

            return 'エラーが発生しました。お手数ですが、店舗に直接お電話ください。';
        }

        session([
            'nonMemberBooking' => $nonMemberBooking,
            'booking' => $booking,
        ]);

        try {
            $util->closeBooked($salon_id, $date, $st_time, $ed_time, $course_master->course_master_id);
        } catch (Exception $e) {
            Log::warning(__METHOD__.'('.__LINE__.') Exception' . $e .' raised when switching open close info with non member!!');
            $booking = Booking::find($booking_id);
            $booking -> delete();
            Log::warning(__METHOD__.'('.__LINE__.') Exception' . $e .' so deleted a booking('.$booking->id>')');
            $nonMemberBooking = NonMemberBooking::find($nonMemberBooking->id);
            $nonMemberBooking -> delete();
            Log::warning(__METHOD__.'('.__LINE__.') Exception' . $e .' so deleted a non member info('.$nonMemberBooking->id.')');
            return 'エラーが発生しました。お手数ですが、店舗に直接お電話ください。';
        }
        Log::notice(__FUNCTION__ . 'NonMemberBooking is saved for non member booking!');

        Mail::to(session('mail'))
            ->send(new NonMemberBookingMail());

        Mail::to($salon->email)
            ->send(new NonMemberBookingMailToSalon());

        Log::info(__FUNCTION__ . 'session delete for non member booking!');
        // 現在使っているセッションを無効化(セキュリティ対策のため)
        $request->session()->invalidate();

        // セッションを無効化を再生成(セキュリティ対策のため)
        $request->session()->regenerateToken();
        return redirect('login')->with('success', '予約を受け付けました。');
    }

    public function startNonUserBooking()
    {
        $dogTypes = Dogtype::all();
        session([
            'dogTypes' => $dogTypes
        ]);
        return view('nonMember.nonMemberBooking1', [
            'dogtypes' => $dogTypes,
        ]);
    }

    public function startNonUserBookingEntry(Request $request)
    {

        $validate_rule = [
            'owner_last_name' => ['required', 'string', 'max:255'],
            'owner_last_name_kana' => ['required', 'string', 'max:255', 'regex:/\A[ァ-ヴー]+\z/u'],
            'owner_first_name' =>  ['required', 'string', 'max:255'],
            'owner_first_name_kana' => ['required', 'string', 'max:255', 'regex:/\A[ァ-ヴー]+\z/u'],
            'mail' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'regex:/^0(\d-?\d{4}|\d{2}-?\d{3}|\d{3}-?\d{2}|\d{4}-?\d|\d0-?\d{4})-?\d{4}$/'],
            'pet_name' => ['required', 'string', 'max:255'],
            'weight' => ['numeric'],
        ];

        $this->validate($request, $validate_rule);

        $dogtype = session('dogTypes')->find($request->dogtype);

        $owner_last_name = $request->owner_last_name;
        $owner_first_name = $request->owner_first_name;
        $owner_last_name_kana = $request->owner_last_name_kana;
        $owner_first_name_kana = $request->owner_first_name_kana;
        $mail = $request->mail;
        $phone = $request->phone;
        $pet_name = $request->pet_name;
        $weight = $request->weight;
        $salons = Salon::all();

        $courses = Course::where('dogtype_id', $dogtype->id)->get();

        Log::info(__METHOD__.'('.__LINE__.') mail' . $mail .' phone:'.$phone);

        session([
            'dogtype' => $dogtype,
            'owner_last_name' => $owner_last_name,
            'owner_first_name' => $owner_first_name,
            'owner_last_name_kana' => $owner_last_name_kana,
            'owner_first_name_kana' => $owner_first_name_kana,
            'pet_name' => $pet_name,
            'courses' => $courses,
            'salons' => $salons,
            'mail' => $mail,
            'phone' => $phone,
            'weight' => $weight,
        ]);

        Log::debug(__FUNCTION__ . session('phone'));

        return view('nonMember.nonMemberSelectcourse', [
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

    public function startNonUserBookingSelectCalender(Request $request)
    {
        $st_date = date('Y-m-d');
        $salon_id = $request->salon;
        $message = $request->message;
        $course_id = $request->course;

        session([
            'salon_id' => $salon_id,
            'course_id' => $course_id,
            'message' => $message,
        ]);

        return $this->getViewForNoUserCalender($salon_id, $course_id, $message, $st_date);
    }

    public function startNonUserBookingSelectCalenderWithStdate(Request $request, $st_date)
    {

        #return $this->getViewForNoUserCalender($salon_id,$course_id,$message,$st_date);
        $salon_id = session('salon_id');
        $course_id = session('course_id');
        $message = session('message');
        return $this->getViewForNoUserCalender($salon_id, $course_id, $message, $st_date);
    }

    private function getViewForNoUserCalender($salon_id, $course_id, $message, $st_date)
    {

        $dogType = session('dogtype');
        $salons = session('salons');


        $salon = $salons->find($salon_id);
        $course = session('courses')->find($course_id);
        session([
            'salon' => $salon,
            'course' => $course,
            'message' => $message,
            'salon_id' => $salon_id,
        ]);

        session(['salon' => $salon]);

        Log::debug(__FUNCTION__ . ' course_id:' . $course_id);

        Log::debug(__METHOD__ . '(' . __LINE__ . ') salon:');
        Log::debug(session('salon'));
        Log::debug(__METHOD__ . '(' . __LINE__ . ') salon_id:' . session('salon_id'));
        $pet_name = session('pet_name');


        $util = new Util();

        $beforeDate = Util::addDays($st_date, -7);
        $afterDate = Util::addDays($st_date, 7);

        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        $step_time = Util::getSetting(30, 'step_time', true);

        $ed_date = Util::addDays($afterDate, -1);

        $timesNum = $util->getTimesNum($st_time, $ed_time, $step_time);

        $ed_date = Util::addDays($afterDate, -1);

        /*
        $allBookings = Booking::all();
        $allDefaultCapacities = DefaultCapacity::all();
        $allTempCapacities = TempCapacity::all();
        $allRegularHoliday = RegularHoliday::all();
        */

        Log::debug(__FUNCTION__ . ' course' . $course_id);

        #$bookingsCalc = new BookingsCalc();

        #$capacities =
        #   $bookingsCalc->getCanBookList($allBookings, $allDefaultCapacities, $allRegularHoliday, $allTempCapacities, $salon, $step_time, $st_date, $ed_date, $course);

        $course_master_id = $course->courseMaster->id;
        if ($course_master_id == 1) {
            $ed_time = 60 * 17 + 1;
        } else {
            $ed_time = 60 * 16 + 1;
        }
        $days = $util->getDaysList($st_date, $ed_date);
        $times = $util->getTimes($st_time, $ed_time, $step_time);
        $openCloseSalonController = new OpenCloseSalonController();
        $course_master_id = $course->courseMaster->id;
        Log::debug(__METHOD__ . '(' . __LINE__ . ') course_master_id: ' . $course_master_id);

        $capacities =
            $openCloseSalonController->makeOpenCloseListFromStdateToEddate($salon_id, $course_master_id, $st_date, $ed_date, $st_time, $ed_time, $step_time);


        $today = date('Y-m-d');
        $maxBookingDate = Util::getEndOfTheMonth($today, 2);

        return view('nonMember.nonMember_booking_calender', [
            'salon' => $salon,
            'dog_type' => $dogType,
            'before_date' => $beforeDate,
            'after_date' => $afterDate,
            'days' => $days,
            'times' => $times,
            'timesNum' => $timesNum,
            'capacities' => $capacities,
            'pet_name' => $pet_name,
            'course' => $course,
            'message' => $message,
            'today' => $today,
            'maxBookingDate' => $maxBookingDate,
        ]);
    }

    public function confirmNonUserBookingSelectCalender(Request $request, $date, $st_time)
    {

        $dogType = session('dogtype');
        $salon = session('salon');
        $pet_name = session('pet_name');
        $course = session('course');
        $message = session('message');
        $date = $request->date;
        $dateStr = Util::dateFormat($date);
        $timeStr = Util::minuteToTime($st_time);

        session([
            'date' => $date,
            'time' => $st_time,
        ]);

        return view('nonMember.confirm', [
            'salon' => $salon,
            'pet_name' => $pet_name,
            'course' => $course,
            'dog_type' => $dogType,
            'date' => $dateStr,
            'time' => $timeStr,
            'message' => $message,
        ]);
    }

    public function saveNonMemberBooking(Request $request)
    {
        Log::debug(__FUNCTION__ . ' 登録なしの予約');
        return '予約する';
    }
}

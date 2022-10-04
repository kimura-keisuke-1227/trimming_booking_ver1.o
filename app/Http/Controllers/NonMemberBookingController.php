<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\NonMemberBooking;
use Illuminate\Support\Facades\Log;
use App\classes\Util;
use App\Models\Course;
use App\Models\Salon;
use App\Models\DefaultCapacity;
use App\Models\RegularHoliday;
use App\Models\TempCapacity;
use App\Models\Setting;
use App\Models\Dogtype;

class NonMemberBookingController extends Controller
{
    //
    public function store(Request $request){
        Log::info(__FUNCTION__ );

        //予約　pet_id = 0 とする　→ pet_id=0ならば登録なしと判定する
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

        $booking -> save();
        Log::info(__FUNCTION__ . 'Booking is saved for non member booking!');

        $booking_id = $booking ->id;
        //非会員の予約を登録
        $nonMemberBooking = new NonMemberBooking();

        $nonMemberBooking -> save();
        Log::info(__FUNCTION__ . 'NonMemberBooking is saved for non member booking!');

        Log::info(__FUNCTION__ . 'session delete for non member booking!');
    }

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
        $step_time = Util::getSetting(30,'step_time',true);

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
            BookingController::getCanBookList($allBookings, $allDefaultCapacities, $allRegularHoliday, $allTempCapacities, $salon, $step_time, $st_date, $ed_date, $course);

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
}

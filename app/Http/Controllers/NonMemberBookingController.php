<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\NonMemberBooking;
use Illuminate\Support\Facades\Log;

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
}

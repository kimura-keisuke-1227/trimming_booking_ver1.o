<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Salon;
use App\Models\Course;

use App\classes\Util;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CancelMailToUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $booking = session('booking');

        $user = Auth::user();
        $date = $booking -> date;
        $st_time = $booking -> time;
        $pet = $booking -> pet;
        $salon = $booking->salon;
        $course = $booking->course;
        $message = $booking->message;

       
        Log::debug(__METHOD__ . ' message：' . $message);

        $mailFrom = 'support@conaffetto-saitama.com';
        $mailFrom = $salon -> email;

        return $this->from($mailFrom) 
        ->subject('予約をキャンセルしました。')
        ->text('email.bookingCancelEmail.bookingCancel',[
            'user' => $user,
            'pet' => $pet,
            'salon' => $salon,
            'course' => $course,
            'message_text' => $message,
            'date' => Util::dbDateToStrDate($date),
            'st_time' => Util::minuteToTime($st_time),
        ]);
    }
}

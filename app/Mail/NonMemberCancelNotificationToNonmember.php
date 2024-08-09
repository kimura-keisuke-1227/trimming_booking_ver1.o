<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Auth;

use App\Models\Salon;
use App\Models\Course;
use Exception;

use App\classes\Util;

use Illuminate\Support\Facades\Log;

class NonMemberCancelNotificationToNonmember extends Mailable
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
        $nonMemberBooking = session('nonMemberBooking');

        $user = Auth::user();
        $date = $booking -> date;
        $st_time = $booking -> st_time;
        $pet = $booking -> pet;
        $salon_id = $booking->salon_id;
        $course = $booking->course;
        $message = $booking->message;
        
        $salon = Salon::find($salon_id);
       
        Log::debug(__METHOD__ . ' message：' . $message);

        // $mailFrom = 'test@testmail.com';
        // $mailFrom = $salon -> email;
        // $mailFrom = Util::getSetting($salon->email,'mailFromSalon',false);

        $mailFrom = Util::getMailFrom();

        if(!$mailFrom){
            throw new Exception("There is NO e-mail from system.");
        }


        return $this->from($mailFrom) 
        ->subject('予約をキャンセルしました。')
        ->text('email.bookingCancelEmail.bookingCancelForNonUser',[
            'nonMemberBooking' => $nonMemberBooking,
            'booking' => $booking,
            'pet' => $pet,
            'salon' => $salon,
            'course' => $course,
            'message_text' => $message,
            'date' => Util::dbDateToStrDate($date),
            'st_time' => Util::minuteToTime($st_time),
        ]);
    }
}

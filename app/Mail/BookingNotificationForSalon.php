<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\classes\Util;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingNotificationForSalon extends Mailable
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
        $user = Auth::user();
        $date = session('date');
        $st_time = session('time');
        $cut_time = session('course')->minute;
        $cut_time_for_show = session('course')->minute_for_show;
        $ed_time = $st_time + $cut_time;
        $ed_time_for_show = $st_time + $cut_time_for_show;
        $pet = session('pet');
        $course = session('course');
        $price =  session('course')->price;
        $salon = session('salon')->id;
        $booking_status = 1;
        $message = session('message');

        Log::debug(__METHOD__ . ' message：' . $message);

        // $salon = session('salon');
        // $mailFrom = 'support@conaffetto-saitama.com';
        // $mailFrom = $salon -> email;

        $mailFrom = Util::getMailFrom();

        if(!$mailFrom){
            throw new Exception("There is NO e-mail from system.");
        }

        $mailFrom = Util::getMailFrom();

        $util = new Util();
        $theUserCameBefore = $util->getTheUserCameBefore($user->id,$date);

        if($theUserCameBefore){
            $theUserCameBefore = '来店履歴あり';
        } else{
            $theUserCameBefore = '来店履歴なし';

        }

        return $this->from($mailFrom) 
        ->subject('予約がありました。')
        ->text('email.bookingNotificationToStaff.bookingNotificationToStaff',[
            'user' => $user,
            'pet' => $pet,
            'salon' => $salon,
            'course' => $course,
            'message_text' => $message,
            'date' => Util::dbDateToStrDate($date),
            'st_time' => Util::minuteToTime($st_time),
            'ed_time_for_show' => Util::minuteToTime($ed_time_for_show),
            'theUserCameBefore' => $theUserCameBefore,
        ]);
    }
}

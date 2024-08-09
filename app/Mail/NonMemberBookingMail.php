<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\classes\Util;

use Exception;

class NonMemberBookingMail extends Mailable
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
        $nonMemberBooking = session('nonMemberBooking');

        $salon = session('salon');
        $booking = session('booking');
        $st_time = Util::minuteToTime($booking->st_time);
        $ed_time = Util::minuteToTime($booking->ed_time);
        $ed_time_for_show = Util::minuteToTime($booking->ed_time_for_show);
        $date = Util::getYMDWFromDbDate($booking->date);

        // $mailFrom = Util::getSetting($salon->email,'mailFromSalon',false);
        $mailFrom = Util::getMailFrom();

        if(!$mailFrom){
            throw new Exception("There is NO e-mail from system.");
        }


        return $this->from($mailFrom) 
        ->subject('予約を受付けました。')
        ->text('email.bookingNotification.bookingNotificationFromNoMember',[
            'nonMemberBooking' => $nonMemberBooking,
            'salon' => $salon,
            'booking' => $booking,
            'st_time' => $st_time,
            'ed_time' => $ed_time,
            'ed_time_for_show' => $ed_time_for_show,
            'date' => $date
        ]);
    }
}

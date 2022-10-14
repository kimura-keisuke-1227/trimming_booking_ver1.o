<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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

        return $this->from($salon->email) 
        ->subject('予約を受付けました。')
        ->text('email.bookingNotification.bookingNotificationFromNoMember',[
            'nonMemberBooking' => $nonMemberBooking,
            'salon' => $salon,
            'booking' => $booking
        ]);
    }
}

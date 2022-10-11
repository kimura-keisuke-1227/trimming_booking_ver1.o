<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\classes\Util;

use Illuminate\Support\Facades\Log;

class ContactAdminMail extends Mailable
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
        $salon = session('salon');
        Log::info(__METHOD__. ' salon:' . $salon);

        $mailAddressFromSalon = $salon -> email;
        $mailSenderName = 'con affetto ' . $salon -> salon_name;
        Log::info(__METHOD__. ' mail from:' . $mailAddressFromSalon . ' '. $mailSenderName);
        return $this
        ->from($mailAddressFromSalon, $mailSenderName)
        ->subject('予約を受付けました。')
        ->text('email.bookingNotification.bookingNotification');
    }
}

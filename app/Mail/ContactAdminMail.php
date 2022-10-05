<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\classes\Util;

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
        $mailAddressFromSalon = Util::getSetting('test@gmail.com','mailFromSalon',false);
        $mailSenderName = Util::getSetting('管理者','mailSenderName',false);

        return $this
        ->from($mailAddressFromSalon, $mailSenderName)
        ->subject('予約を受付けました。')
        ->text('email.bookingNotification.bookingNotification');
    }
}

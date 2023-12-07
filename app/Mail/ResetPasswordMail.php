<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $recipientEmail;
    public $subject;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recipientEmail, $subject, $token)
    {
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->token = $token;

    }

    public function build($recipientEmail, $subject, $token)
    {
        $senderEmail = config('mail.from.address');
        $senderName = config('mail.from.name');

        return $this->view('resetPassword.blade.php', ['token' => $token])
            ->from($senderEmail, $senderName)
            ->to($recipientEmail)
            ->subject($subject);
    }


}

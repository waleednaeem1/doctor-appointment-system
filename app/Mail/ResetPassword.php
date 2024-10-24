<?php

namespace App\Mail;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendContact.
 */
class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    public $notifiable;

    /**
     * SendContact constructor.
     *
     * @param Request $request
     */
    public function __construct($notifiable)
    {
        $this->notifiable = $notifiable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        return $this->to($this->notifiable->email)
            ->view('frontend.mail.reset-email', ['otp' => $this->notifiable->token, 'notifiable' => $this->notifiable])
            ->subject('Reset Password Notification')
            ->from('info@searchavet.com','SearchAVet');
    }
}

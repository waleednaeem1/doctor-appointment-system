<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendContact.
 */
class AddDoctor extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    public $data;
    public  $password;

    /**
     * SendContact constructor.
     *
     * @param Request $request
     */
    public function __construct($data, $password)
    {
        $this->data = $data;
        $this->password =  $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        // return $this->to($this->data['email'])->bcc([ 'jennifer@vetandtech.com','farhan@germedusa.com','info@vetandtech.com','mohsin.gmit@gmail.com','waleed.gmit@gmail.com'])
        return $this->to($this->data['email'])->bcc(['waleed.gmit@gmail.com'])
            ->view('frontend.mail.new_doctor',['data' => $this->data,'password' => $this->password])
            ->subject('Assigned you as a Doctor' )
            ->from('no-reply@searchavet.com')
            ->replyTo('no-reply@searchavet.com', 'No-Reply');
    }
}
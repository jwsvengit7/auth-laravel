<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpSend extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $email;

    public function __construct($otp,$email)
    {
        $this->otp = $otp;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Your OTP Code')
                    ->view('emails') // Create a corresponding blade view
                    ->with(['otp' => $this->otp,"email"=>$this->email]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordForNewAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->view('emails.send_password_new_admin')->with(['email' => $this->email, 'password' => $this->password]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotify extends Mailable
{
    use Queueable, SerializesModels;

    public $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function build()
    {
        $subject = 'Notifications from the LINE Bot system - LINE OA support'; // tiêu đề cho mail

        return $this->subject($subject)->view('emails.mail_notify')->with(['content' => $this->content]);
    }
}

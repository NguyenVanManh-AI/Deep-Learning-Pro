<?php

namespace App\Jobs;

use App\Mail\NotifyMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailWelCome implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;

    public $name;

    public function __construct($email, $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    public function handle()
    {
        $email = $this->email;
        $name = $this->name;

        // Send mail welcome
        Mail::to($email)->send(new NotifyMail($name));
        info("Email Welcome sent to $email with Name: $name");
        Log::info("Email Welcom sent to $email with Name: $name");
    }
}

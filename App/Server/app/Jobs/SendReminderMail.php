<?php

namespace App\Jobs;

use App\Mail\ReminderMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReminderMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $email = $this->user->email;
        $name = $this->user->name;
        // Send mail reminder
        Mail::to($email)->send(new ReminderMail($this->user));
        Log::info("Email Reminder sent to $email with Name: $name");
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\DailyEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'command:name';
    protected $signature = 'users:sendmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail to all users by running this command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new DailyEmail($user));
        }
        // return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Jobs\SendReminderMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class ReminderMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sendreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder mail to all users by running this command';

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
            Queue::push(new SendReminderMail($user));
        }
        // return 0;
    }
}

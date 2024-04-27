<?php

namespace App\Console;

use App\Jobs\SendDailyEmail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendMail::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('users:sendreminder')->dailyAt('22:10');
        // $schedule->command('users:sendreminder')->everyMinute();
        $schedule->command('user:statistical')->everyMinute();
        $schedule->command('user:broadcast')->everyMinute();
        // $schedule->command('users:sendmail')->everyMinute(); // thực hiện lệnh command

        // $schedule->command('users:sendmail')->cron('* * * * *'); // thực hiện lệnh command
        // $schedule->command('users:sendmail')->hourly(); // thực hiện lệnh command
        // $schedule->command('minute:update')->hourly();
        // $schedule->job(new SendDailyEmail)->daily();
        // $schedule->job(new SendDailyEmail)->everyMinute(); // Mỗi phút
        // $schedule->job(new SendDailyEmail)->everySecond(); // Mỗi giây
        // $schedule->job(new SendDailyEmail)->hourly(); // Mỗi giờ
        // $schedule->job(new SendDailyEmail)->weeklyOn(1, '8:00'); // Mỗi tuần (vào thứ 2 lúc 8h)
        // $schedule->job(new SendDailyEmail)->monthlyOn(1, '8:00'); // Mỗi tháng (ngày 1 lúc 8h)
        // $schedule->job(new SendDailyEmail)->yearlyOn(1, 1, '8:00'); // Mỗi năm (ngày 1 tháng 1 lúc 8h)
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

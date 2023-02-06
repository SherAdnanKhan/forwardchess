<?php

namespace App\Console;

use App\Console\Commands\RunQueue;
use Illuminate\Support\Facades\Log;
use App\Console\Commands\RetryQueue;
use App\Console\Commands\ProcessOrders;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\AssignABTestingToUsers;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        RunQueue::class,
        RetryQueue::class,
        ProcessOrders::class,
        AssignABTestingToUsers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        Log::debug('schedule');

        $schedule->command('process:queue')->everyMinute();
        $schedule->command('ab:testing')->everyMinute();

        $schedule->command('retry:queue')->twiceDaily(1, 13);

        $schedule
            ->command('process:orders')
            ->timezone('Europe/Bucharest')
            ->dailyAt('22:20');
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

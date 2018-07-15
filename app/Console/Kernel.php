<?php

namespace App\Console;

use App\Console\Commands\CreateFirstCron;
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
        Commands\ImportStudentPictures::class,
        Commands\ImportNewcomers::class,
        Commands\RenderNewcomers::class,
        Commands\PutScheduledEmailToQueue::class,
        Commands\SetPassword::class,
        CreateFirstCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inte:emails-to-queue')->everyMinute();
    }
}

<?php

namespace App\Console;

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
        //'App\Console\Commands\Marker',
        'App\Console\Commands\Marker1',
        'App\Console\Commands\Marker11',
        'App\Console\Commands\Marker12',
        'App\Console\Commands\Marker13',
        'App\Console\Commands\Marker14',
        'App\Console\Commands\Marker2',
        'App\Console\Commands\TtMarker',
        'App\Console\Commands\PdfFinder',
        'App\Console\Commands\Dumper',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('mark')
//            ->everyFiveMinutes();

//        $schedule->command('mark1')
//            ->everyMinute()->withoutOverlapping();

        $schedule->command('mark11')
            ->everyMinute()->withoutOverlapping();
        $schedule->command('mark12')
            ->everyMinute()->withoutOverlapping();
        $schedule->command('mark13')
            ->everyMinute()->withoutOverlapping();
        $schedule->command('mark14')
            ->everyMinute()->withoutOverlapping();
        $schedule->command('mark2')
            ->everyMinute()->withoutOverlapping();

        $schedule->command('pdf')
            ->dailyAt('4:00');
        $schedule->command('dbdump')
            ->dailyAt('4:00');
//            ->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

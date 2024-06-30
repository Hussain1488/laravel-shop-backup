<?php

namespace App\Console;

use App\Console\Commands\AfterInstallmentMessageCommand;
use App\Console\Commands\BeforInstallmentMessageCommand;
use App\Console\Commands\DatabaseBackup;
use App\Console\Commands\StoreCreditReset;
use App\Jobs\CalculateViewers;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Session;
use Morilog\Jalali\Jalalian;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // InstallCommand::class
        StoreCreditReset::class,
        BeforInstallmentMessageCommand::class,
        AfterInstallmentMessageCommand::class,
        DatabaseBackup::class,
    ];

    /**
     * Define the queues used by the application.
     *
     * @var array
     */
    protected $queues = [
        'default',
    ];


    /**
     * Create a new console kernel instance.
     *
     * @return void
     */

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // run the queue worker "without overlapping"
        // this will only start a new worker if the previous one has died
        $schedule->command($this->getQueueCommand())
            ->everyMinute()
            ->withoutOverlapping();

        // restart the queue worker periodically to prevent memory issues
        $schedule->command('queue:restart')
            ->hourly();

        $schedule->call(function () {
            option_update('schedule_run', now());
        })->everyMinute();

        $schedule->job(new CalculateViewers)->dailyAt('00:00');


        $schedule->command('command:StoreCreditReset')->when(function () {
            $day = Jalalian::now()->format('d');
            $hour = Jalalian::now()->format('H:i');
            $cornjobDay = option('cornjob_call_day') != null ? option('cornjob_call_day') : 1;
            $status = option('store_reccredition_status') == 'on' ? true : false;
            return ($day == $cornjobDay && $hour == '00:00' && $status);
        });

        $schedule->command('command:BeforInstallmentMessageCommand')->daily()->when(function () {
            $status = option('message_send_befor_status') == 'on' ? true : false;
            return $status; // Replace this with your actual condition
        });
        $schedule->command('command:AfterInstallmentMessageCommand')->daily()->when(function () {
            $status = option('message_send_after_status') == 'on' ? true : false;
            return $status; // Replace this with your actual condition
        });
        $this->shouldRunBackup($schedule);
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

    /**
     * Configure the queues used by the application.
     *
     * @return void
     */
    protected function configureQueue()
    {
        // Additional queue configuration if needed
    }

    /**
     * Get the full queue command.
     *
     * @return string
     */
    protected function getQueueCommand()
    {
        $params = implode(' ', [
            // '--daemon',
            '--tries=3',
            '--sleep=3',
            '--queue=' . implode(',', $this->queues),
        ]);

        return sprintf('queue:work %s', $params);
    }

    protected function shouldRunBackup($schedule)
    {
        $status = option('backup_status') === 'on';
        $period = option('backup_period');

        switch ($period) {
            case 'daily':
                return $status && $this->shouldRunDaily($schedule);
            case 'weekly':
                return $status && $this->shouldRunWeekly($schedule);
            case 'monthly':
                return $status && $this->shouldRunMonthly($schedule);
            default:
                return false; // Unknown backup period
        }
    }
    protected function shouldRunDaily($schedule)
    {
        $schedule->command('command:DatabaseBackup')->daily();
        return true;
    }
    protected function shouldRunWeekly($schedule)
    {
        $schedule->command('command:DatabaseBackup')->weekly();
        return true;
    }
    protected function shouldRunMonthly($schedule)
    {
        $schedule->command('command:DatabaseBackup')->monthly();
        return true;
    }
}

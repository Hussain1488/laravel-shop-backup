<?php

namespace App\Console\Commands;

use App\Jobs\CreateBackup;
use App\Models\CornjobModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DatabaseBackup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // CreateBackup::dispatchAfterResponse();
        Artisan::call('backup:run --disable-notifications');
        CornjobModel::create([
            'name' => 'بک آپ دیتابیس',
            'description' => 'کرن جاب دوره ای بکاب دیتابیس انجام شد!'
        ]);
        return 0;
    }
}

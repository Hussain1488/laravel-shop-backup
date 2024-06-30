<?php

namespace App\Console\Commands;

use App\Models\BankAccount;
use App\Models\banktransaction;
use App\Models\CornjobModel;
use App\Models\createstore;
use App\Models\createstoretransaction;
use Illuminate\Support\Facades\Log;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;

class StoreCreditReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:storeCreditReset';


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
        // Session::put('storeCredit', true);
        $bank_id = BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 25);
        })->first();

        $stores = createstore::all();
        foreach ($stores as $key) {
            $ex_credit = $key->storecredit;
            $key->storecredit = $key->conrn_job_reccredite;
            $trans_id = createstoretransaction::storeTransaction($key, $key->conrn_job_reccredite, true, 3, 0, $key->user_id, null,  'اعتبار دهی دوره ای فروشگاه');
            banktransaction::transaction($bank_id->id, $ex_credit, true, $trans_id, 'store');
            banktransaction::transaction($bank_id->id, $key->conrn_job_reccredite, false, $trans_id, 'store');
            $key->save();
        }

        CornjobModel::create([
            'name' => 'اعتبار فروشگاه',
            'description' => 'اعتبار دوره ای فروشگاه ها با موفقیت انجام شد',
        ]);
        log::info('Cron job executed successfully at ' . now());
        // Session::push('storeCredit', true);
        return 0;
    }
}

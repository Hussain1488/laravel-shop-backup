<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeMakeinstallmentsmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `makeinstallmentsms` MODIFY `typeofpayment` ENUM('cash', 'monthly_installment', 'weekly_installment', 'w_none_profit', 'm_none_profit') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `makeinstallmentsms` MODIFY `typeofpayment` ENUM('cash', 'monthly_installment', 'weekly_installment') NOT NULL");
    }
}

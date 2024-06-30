<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToCreatestoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('createstores', function (Blueprint $table) {
            $table->enum('no_profit_installment', ['yes', 'no'])->default('yes')->after('feepercentage');
            $table->tinyInteger('no_profit_fee')->default(15)->after('no_profit_installment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('createstores', function (Blueprint $table) {
            $table->dropColumn('no_profit_installment');
            $table->dropColumn('no_profit_fee');
        });
    }
}

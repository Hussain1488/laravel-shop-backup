<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanktransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banktransactions', function (Blueprint $table) {
            $table->id();
            $table->string('namebank');
            $table->bigInteger('bankbalance');
            $table->bigInteger('transactionprice');
            $table->enum('type', ['deposit', 'withdraw'])->index();
            $table->date('transactionsdate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banktransactions');
    }
}

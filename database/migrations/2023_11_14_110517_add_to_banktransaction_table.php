<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToBanktransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banktransactions', function (Blueprint $table) {
            $table->unsignedBigInteger('buyer_trans_id')->nullable();
            $table->unsignedBigInteger('store_trans_id')->nullable();
            $table->foreign('buyer_trans_id')->references('id')->on('buyertransactions');
            $table->foreign('store_trans_id')->references('id')->on('createstoretransactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banktransaction', function (Blueprint $table) {
        });
    }
}

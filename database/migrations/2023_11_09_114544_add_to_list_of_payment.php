<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToListOfPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('list_of_payment', function (Blueprint $table) {
            $table->bigInteger('final_price')->default(0);
            $table->unsignedBigInteger('trans_id')->nullable();
            $table->foreign('trans_id')->references('id')->on('createstoretransactions')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_of_payment', function (Blueprint $table) {
            // $table->bigInteger('final_price')->default(0);
        });
    }
}

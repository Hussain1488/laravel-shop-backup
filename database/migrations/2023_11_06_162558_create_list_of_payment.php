<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListOfPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_of_payment', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('list_id')->unique();
            $table->unsignedBigInteger('store_id');
            $table->bigInteger('depositamount');
            $table->bigInteger('shabanumber');
            $table->text('factor');
            $table->date('depositdate');
            $table->tinyInteger('status')->default(0);
            $table->foreign('store_id')->references('id')->on('createstores')->onDelete('cascade');
            $table->index('store_id');
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
        Schema::dropIfExists('list_of_payment');
    }
}

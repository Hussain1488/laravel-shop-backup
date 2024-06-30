<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyertransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyertransactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('flag', ['1', '0']);
            $table->date('datetransaction');
            $table->enum('typeoftransaction', ['1', '0']);
            $table->string('description', 500)->default('بدون توضیح');
            $table->bigInteger('price');
            $table->bigInteger('finalprice');
            $table->bigInteger('documentnumber');
            $table->index('user_id');
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
        Schema::dropIfExists('buyertransactions');
    }
}

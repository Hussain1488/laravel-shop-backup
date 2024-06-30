<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMakeinstallmentsmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('makeinstallmentsms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('status');
            $table->integer('Creditamount');
            $table->enum('typeofpayment', ['cash', 'monthly_installment', 'weekly_installment']);
            $table->string('numberofinstallments');
            $table->string('prepaidamount');
            $table->string('amounteachinstallment');
            $table->boolean('buyerstatus');
            $table->boolean('paymentstatus');
            $table->integer('statususer');
            $table->foreign('seller_id')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index('seller_id');
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
    }
}

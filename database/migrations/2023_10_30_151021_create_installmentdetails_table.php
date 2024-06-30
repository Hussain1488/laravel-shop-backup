<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installmentdetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('installment_id')->nullable();
            $table->foreign('installment_id')->references('id')->on('makeinstallmentsms')->onDelete('cascade');
            $table->enum("state", ['1', '0']);
            $table->date("duedate");
            $table->bigInteger("installmentnumber");
            $table->string("installmentprice");
            $table->index('installment_id');
            $table->boolean("paymentstatus");

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
        Schema::dropIfExists('installmentdetails');
    }
}

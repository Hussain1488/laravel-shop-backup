<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentdetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("list_of_payment_id")->index();
            $table->bigInteger("Issuetracking");
            $table->unsignedBigInteger("bank_id")->index();
            $table->text("documentpayment");
            $table->foreign("list_of_payment_id")->references("id")->on("list_of_payment")->onDelete('cascade');
            $table->foreign("bank_id")->references("id")->on("createbankaccounts")->onDelete('cascade');
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
        Schema::dropIfExists('paymentdetails');
    }
}

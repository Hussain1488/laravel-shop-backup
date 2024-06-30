<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAcountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('createbankaccounts', function (Blueprint $table) {
            $table->id();
            $table->string('bankname');
            $table->bigInteger('accountnumber');
            $table->unsignedBigInteger('account_type_id');
            $table->unsignedBigInteger('gateway_id')->nullable()->index();
            $table->foreign('account_type_id')->references('id')->on('type_of_account')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('gateway_id')->references('id')->on('gateways')->onUpdate('cascade')->onDelete('cascade');
            $table->index('account_type_id');
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
        //
    }
}

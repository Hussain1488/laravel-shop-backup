<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debtor_trans_id')->index();
            $table->unsignedBigInteger('creditor_trans_id')->index();
            $table->text('documents')->nullable();
            $table->bigInteger('numberofdocuments')->nullable();
            $table->string('description', 710)->nullable();
            $table->foreign('creditor_trans_id')->references('id')->on('banktransactions');
            $table->foreign('debtor_trans_id')->references('id')->on('banktransactions');
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
        Schema::dropIfExists('account_document');
    }
}

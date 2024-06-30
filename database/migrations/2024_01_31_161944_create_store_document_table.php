<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('store_id');
            $table->text('documents')->nullable();
            $table->enum('type', ['withdraw', 'deposit'])->nullable();
            $table->bigInteger('numberofdocuments')->nullable();
            $table->string('description', 710)->nullable();
            $table->foreign('transaction_id')->references('id')->on('banktransactions');
            $table->foreign('store_id')->references('id')->on('createstores')->onDelete('cascade')->onUpdate('cascade');
            $table->index('store_id');
            $table->index('transaction_id');
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
        Schema::dropIfExists('store_document');
    }
}

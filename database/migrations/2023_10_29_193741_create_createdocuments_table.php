<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreatedocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('createdocuments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('user_id');
            $table->text('documents')->nullable();
            $table->enum('type', ['withdraw', 'deposit'])->nullable();
            $table->bigInteger('numberofdocuments')->nullable();
            $table->string('description', 710)->nullable();
            $table->foreign('transaction_id')->references('id')->on('banktransactions');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->index('user_id');
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
        Schema::dropIfExists('createdocuments');
    }
}

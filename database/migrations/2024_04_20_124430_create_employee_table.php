<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->string('unique_id', 6)->nullable();
            $table->string('full_name', 90);
            $table->string('role', 90);
            $table->string('title');
            $table->string('photo', 500);
            $table->integer('rating')->default(1);
            $table->enum('default_rating', ['none', 1, 2, 3, 4, 5])->default('none');
            $table->bigInteger('reviews_count')->default(0);
            $table->text('QR_code_link')->nullable();
            $table->text('description');
            $table->foreign('shop_id')->references('id')->on('createstores')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('employee');
    }
}

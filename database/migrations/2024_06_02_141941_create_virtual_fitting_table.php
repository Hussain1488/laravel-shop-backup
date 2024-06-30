<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualFittingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_fitting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gallery_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('photo', 256);
            $table->string('downloadable_photo', 256)->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('gallery_id')->references('id')->on('galleries');
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
        Schema::dropIfExists('virtual_fitting');
    }
}

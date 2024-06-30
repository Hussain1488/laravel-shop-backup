<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopServiceReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_service_review', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('createstores')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('shop_service')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->integer('rating');
            $table->enum('type', ['shop', 'service', 'employee'])->nullable();
            $table->enum('suggest', ['yes', 'no', 'not_sure'])->nullable();
            $table->enum('status', ['accepted', 'pending', 'rejected'])->default('pending');
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);
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
        Schema::dropIfExists('shop_service_review');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceReviewPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_review_point', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('review_id');
            $table->string('text');
            $table->enum('type', ['positive', 'negative']);
            $table->foreign('review_id')->references('id')->on('shop_service_review')->onDelete('cascade');
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
        Schema::dropIfExists('service_review_point');
    }
}

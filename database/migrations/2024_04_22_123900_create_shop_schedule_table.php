<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->enum('day', ['su', 'sa', 'mo', 'tu', 'we', 'th', 'fr']);
            $table->string('time');
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
        Schema::dropIfExists('shop_schedule');
    }
}

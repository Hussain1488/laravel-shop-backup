<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreatestoretransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('createstoretransactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('createstores')->onDelete('cascade');;
            $table->integer('flag');
            $table->date('datetransaction');
            $table->string('typeoftransaction');
            $table->string('description', 500)->default('بدون توضیح');
            $table->bigInteger('price');
            $table->bigInteger('finalprice');
            $table->bigInteger('documentnumber');
            $table->index('store_id');
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
        Schema::dropIfExists('createstoretransactions');
    }
}

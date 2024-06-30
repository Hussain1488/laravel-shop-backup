<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToCreatestoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('createstores', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on(
                'createbankaccounts'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('createstore', function (Blueprint $table) {
            //
        });
    }
}

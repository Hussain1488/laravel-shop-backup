<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNamebankColumnInBanktransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('banktransactions', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id')->change();
            $table->foreign('bank_id')->references('id')->on('createbankaccounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banktransactions', function (Blueprint $table) {
            //
        });
    }
}

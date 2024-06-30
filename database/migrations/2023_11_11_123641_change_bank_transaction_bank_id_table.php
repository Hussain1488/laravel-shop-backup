<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBankTransactionBankIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banktransactions', function (Blueprint $table) {
            $table->renameColumn('namebank', 'bank_id');
        });
    }

    public function down()
    {
    }
}

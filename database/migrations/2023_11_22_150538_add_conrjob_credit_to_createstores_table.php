<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConrjobCreditToCreatestoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('createstores', function (Blueprint $table) {
            $table->bigInteger('conrn_job_reccredite')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('createstores', function (Blueprint $table) {
            $table->dropColumn('conrn_job_reccredite');
        });
    }
}

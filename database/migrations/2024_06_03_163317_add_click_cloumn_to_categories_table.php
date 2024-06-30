<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClickCloumnToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('guid')->unique()->nullable();
            $table->string('code')->nullable();
            $table->string('alias')->nullable();
            $table->string('parent_guid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('guid');
            $table->dropColumn('code');
            $table->dropColumn('alias');
            $table->dropColumn('parent_guid');
        });
    }
}

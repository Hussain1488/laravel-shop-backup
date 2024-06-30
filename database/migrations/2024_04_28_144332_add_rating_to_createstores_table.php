<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingToCreatestoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('createstores', function (Blueprint $table) {
            $table->text('description')->nullable()->after('addressofstore');
            $table->string('location', 300)->nullable()->after('description');
            $table->string('photo')->after('user_id')->nullable();
            $table->integer('rating')->default(1)->after('photo');
            $table->enum('default_rating', ['none', 1, 2, 3, 4, 5])->default('none')->after('rating');
            $table->bigInteger('reviews_count')->default(0)->after('rating');
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
            $table->dropColumn('description');
            $table->dropColumn('location');
            $table->dropColumn('photo');
            $table->dropColumn('rating');
            $table->dropColumn('default_rating');
            $table->dropColumn('reviews_count');
        });
    }
}

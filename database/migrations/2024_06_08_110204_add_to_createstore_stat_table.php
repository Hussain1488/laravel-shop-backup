<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToCreatestoreStatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('createstores', function (Blueprint $table) {
            $table->string('phone', 12)->nullable()->after('nameofstore');
        });
        Schema::table('participant_posts', function (Blueprint $table) {
            $table->enum('state', ['valid', 'pending', 'not-valid'])->default('pending')->after('video');
            $table->string('caption', 300)->nullable()->after('state');
        });
        Schema::table('participants', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('type', ['post', 'match'])->default('post')->after('slug');
            $table->enum('status', ['published', 'finished', 'pending', 'deactive'])->default('deactive');
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
            $table->dropColumn('phone');
        });
        Schema::table('participant_posts', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->dropColumn('caption');
        });
        Schema::table('participants', function (Blueprint $table) {
            // $table->dropColumn('user_id');
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('status');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'none'])->default('none');
            $table->integer('Id_number')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('image')->nullable();
            $table->string('level')->default('user');
            $table->string('password');
            $table->index('username');
            $table->enum('comment_permision', ['valid', 'not_valid'])->default('valid');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

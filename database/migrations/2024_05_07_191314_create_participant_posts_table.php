<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_id');
            $table->string('name')->nullable();
            $table->text('photo')->nullable();
            $table->string('video')->nullable();
            $table->enum('rating', [1, 2, 3, 4, 5])->default(1);
            $table->integer('like_count')->default(0);
            $table->integer('dislike_count')->default(0);
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('participant_posts');
    }
}

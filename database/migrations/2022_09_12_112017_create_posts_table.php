<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('caption');
            $table->string('image');
            $table->timestamps();

            $table->index('user_id');

            //delete on cascade when user deletes account, corresponding posts are deleted 2
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //references column name of "id" on "users" table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}

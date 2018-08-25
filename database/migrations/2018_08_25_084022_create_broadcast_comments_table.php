<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBroadcastCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broadcast_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('comment');
            $table->integer('user_id')->unsigned();
            $table->integer('broadcast_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('broadcast_id')->references('id')->on('broadcasts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broadcast_comments');
    }
}

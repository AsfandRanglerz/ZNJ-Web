<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_favourites_id');
            $table->foreign('chat_favourites_id')->references('id')->on('chat_favourites')->onDelete('cascade');
            $table->string('sender_type')->nullable();
            $table->string('body')->nullable();
            $table->tinyInteger('seen')->default(0);
            $table->tinyInteger('user_deleted')->default(0);
            $table->tinyInteger('admin_deleted')->default(0);
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
        Schema::dropIfExists('chat_messages');
    }
}

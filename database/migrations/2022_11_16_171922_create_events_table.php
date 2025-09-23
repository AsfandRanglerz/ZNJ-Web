<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('location')->nullable();
            $table->text('about_event')->nullable();
            $table->enum('event_type',['Private','Public'])->default('Private');
            $table->string('date')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->enum('joining_type',['Paid','Free'])->default('Paid');
            $table->string('price')->nullable();
            $table->integer('seats')->nullable();
            $table->text('description')->nullable();
            $table->enum('hiring_entertainers_status',['hired','open for hiring'])->default('hired');
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
        Schema::dropIfExists('events');
    }
}

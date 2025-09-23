<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('about_venue')->nullable();
            $table->string('description')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('stands')->nullable();
            $table->double('area')->nullable();
            // $table->enum('offer_cattering',['Yes','No'])->default('Yes');
            $table->string('opening_time')->nullable();
            $table->string('closing_time')->nullable();
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
        Schema::dropIfExists('venues');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenuePricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venue_pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venues_id');
            $table->foreign('venues_id')->references('id')->on('venues')->onDelete('cascade');
            $table->string('day')->nullable();
            $table->string('price')->nullable();
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
        Schema::dropIfExists('venue_pricings');
    }
}

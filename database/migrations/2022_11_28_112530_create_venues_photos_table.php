<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenuesPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_id');
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->string('photos')->nullable();
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
        Schema::dropIfExists('venues_photos');
    }
}

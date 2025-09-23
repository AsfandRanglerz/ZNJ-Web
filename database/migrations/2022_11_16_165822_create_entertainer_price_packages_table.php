<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntertainerPricePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entertainer_price_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entertainer_details_id');
            $table->foreign('entertainer_details_id')->references('id')->on('entertainer_details')->onDelete('cascade');
            $table->string('time')->nullable();
            $table->string('price_package')->nullable();
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
        Schema::dropIfExists('entertainer_price_packages');
    }
}

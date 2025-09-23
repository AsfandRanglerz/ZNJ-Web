<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recruiter_id')->nullable();
            $table->foreign('recruiter_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('entertainer_id')->nullable();
            $table->foreign('entertainer_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('venue_id')->nullable();
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->unsignedBigInteger('entertainer_price_package_id')->nullable();
            $table->foreign('entertainer_price_package_id')->references('id')->on('entertainer_price_packages')->onDelete('cascade');
            $table->string('payment')->nullable();
            $table->string('customer_id')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('payments');
    }
}

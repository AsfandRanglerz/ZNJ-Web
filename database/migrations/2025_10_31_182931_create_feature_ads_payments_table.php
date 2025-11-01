<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureAdsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_ads_payments', function (Blueprint $table) {
            $table->id();

            // Link to main entities
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('venue_id')->nullable();
            $table->unsignedBigInteger('entertainer_detail_id')->nullable();

            // Link to feature ads packages (each has its own table)
            $table->unsignedBigInteger('event_feature_ads_package_id')->nullable();
            $table->unsignedBigInteger('venue_feature_ads_package_id')->nullable();
            $table->unsignedBigInteger('entertainer_feature_ads_package_id')->nullable();

            // Payment details
            $table->string('order_id')->unique();
            $table->string('session_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();

            // Foreign Keys (linking to actual tables)
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->foreign('entertainer_detail_id')->references('id')->on('entertainer_details')->onDelete('cascade');

            $table->foreign('event_feature_ads_package_id')->references('id')->on('event_feature_ads_packages')->onDelete('cascade');
            $table->foreign('venue_feature_ads_package_id')->references('id')->on('venue_feature_ads_packages')->onDelete('cascade');
            $table->foreign('entertainer_feature_ads_package_id')->references('id')->on('entertainer_feature_ads_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_ads_payments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeatureStatusToVenueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->string('feature_status')->default('0')->after('closing_time');
            $table->unsignedBigInteger('venue_feature_ads_packages_id')->after('feature_status')->nullable();
            $table->foreign('venue_feature_ads_packages_id')->references('id')->on('venue_feature_ads_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('venues', function (Blueprint $table) {
            //
        });
    }
}

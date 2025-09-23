<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeatureStatusToEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('feature_status')->default('0')->after('hiring_entertainers_status');
            $table->unsignedBigInteger('event_feature_ads_packages_id')->after('feature_status')->nullable();
            $table->foreign('event_feature_ads_packages_id')->references('id')->on('event_feature_ads_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
}

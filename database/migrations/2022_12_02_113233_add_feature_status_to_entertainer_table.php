<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeatureStatusToEntertainerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entertainer_details', function (Blueprint $table) {
            $table->string('feature_status')->default('0')->after('description');
            $table->unsignedBigInteger('entertainer_feature_ads_packages_id')->after('feature_status')->nullable();
            $table->foreign('entertainer_feature_ads_packages_id')->references('id')->on('entertainer_feature_ads_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entertainer_details', function (Blueprint $table) {
            //
        });
    }
}

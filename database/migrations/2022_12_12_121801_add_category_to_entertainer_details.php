<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryToEntertainerDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entertainer_details', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->after('about_yourself')->nullable();
            $table->foreign('category_id')->references('id')->on('talent_categories')->onDelete('cascade');
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

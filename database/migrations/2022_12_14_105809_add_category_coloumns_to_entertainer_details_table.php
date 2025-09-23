<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryColoumnsToEntertainerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entertainer_details', function (Blueprint $table) {
            $table->string('awards')->after('image')->nullable();
            $table->string('height')->after('image')->nullable();
            $table->string('weight')->after('image')->nullable();
            $table->string('waist')->after('image')->nullable();
            $table->string('shoe_size')->after('image')->nullable();
            $table->string('own_equipments')->after('image')->nullable();
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

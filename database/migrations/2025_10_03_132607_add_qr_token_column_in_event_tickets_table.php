<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrTokenColumnInEventTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_tickets', function (Blueprint $table) {
            $table->string('qr_token')->nullable()->after('qr_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_tickets', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
}

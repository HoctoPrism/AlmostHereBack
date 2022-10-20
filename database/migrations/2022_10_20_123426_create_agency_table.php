<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency', function (Blueprint $table) {
            $table->increments('agency_id');
            $table->string('agency_name', 50);
            $table->string('agency_url', 255);
            $table->string('agency_fare_url', 255)->nullable();
            $table->text('agency_timezone');
            $table->string('agency_phone', 25)->nullable();
            $table->string('agency_lang', 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agency');
    }
};

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
        Schema::table('trips', function (Blueprint $table) {
            $table->foreign('shape_id')->references('shape_id')->on('shapes');
            $table->foreign('route_id')->references('route_id')->on('routes');
            $table->foreign('service_id')->references('service_id')->on('calendar');
        });

        Schema::table('stop_times', function (Blueprint $table) {
            $table->foreign('stop_id')->references('stop_id')->on('stops');
            $table->foreign('trip_id')->references('trip_id')->on('trips');
        });

        Schema::table('frequencies', function (Blueprint $table) {
            $table->foreign('trip_id')->references('trip_id')->on('trips');
        });

        Schema::table('routes', function (Blueprint $table) {
            $table->foreign('agency_id')->references('agency_id')->on('agency');
        });

        Schema::table('calendar_dates', function (Blueprint $table) {
            $table->foreign('service_id')->references('service_id')->on('calendar');
        });

        Schema::table('stops', function (Blueprint $table) {
            $table->foreign('parent_station')->references('stop_id')->on('stops');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('favorite_id')->references('favorite_id')->on('favorites')->onDelete('cascade');
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->foreign('route_id')->references('route_id')->on('routes');
            $table->foreignId('user_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};

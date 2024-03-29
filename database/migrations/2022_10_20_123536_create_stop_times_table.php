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
        Schema::create('stop_times', function (Blueprint $table) {
            $table->string('trip_id');
            $table->string('stop_id');
            $table->integer('stop_sequence');
            $table->time('arrival_time');
            $table->time('departure_time');
            $table->string('stop_headsign', 50)->nullable();
            $table->integer('pickup_type')->nullable();
            $table->integer('drop_off_type')->nullable();
            $table->integer('shape_dist_traveled')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stop_times');
    }
};

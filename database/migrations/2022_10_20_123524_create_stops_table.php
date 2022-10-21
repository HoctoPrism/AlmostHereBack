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
        Schema::create('stops', function (Blueprint $table) {
            $table->bigIncrements('stop_id');
            $table->string('stop_code', 50)->nullable();
            $table->string('stop_name', 50)->nullable();
            $table->decimal('stop_lat', 8, 6);
            $table->decimal('stop_lon',8 , 6);
            $table->integer('location_type')->nullable();
            $table->foreignId('parent_station')->references('stop_id')->on('stops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stops');
    }
};

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
        Schema::create('routes', function (Blueprint $table) {
            $table->string('route_id')->primary();
            $table->string('agency_id');
            $table->string('route_short_name', 20);
            $table->string('route_long_name', 100);
            $table->string('route_desc', 100)->nullable();
            $table->integer('route_type');
            $table->string('route_url', 255)->nullable();
            $table->string('route_color', 15)->nullable();
            $table->string('route_text_color', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
};

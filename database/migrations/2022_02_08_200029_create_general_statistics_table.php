<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('clean_area_year');
            $table->integer('clean_area_week');
            $table->integer('clean_area_monthly');
            $table->integer('unexplosive_year');
            $table->integer('unexplosive_week');
            $table->integer('unexplosive_monthly');
            $table->integer('pedestrian_year');
            $table->integer('pedestrian_week');
            $table->integer('pedestrian_monthly');
            $table->integer('tank_year');
            $table->integer('tank_week');
            $table->integer('tank_monthly');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_statistics');
    }
}

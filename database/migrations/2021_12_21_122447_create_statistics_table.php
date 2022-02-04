<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('tank')->nullable()->comment('tank eleyhine mina');
            $table->integer('clean_area')->nullable()->comment('temizlenen erazi');
            $table->integer('unexplosive')->nullable()->comment('Partlamayan hərbi sursat');
            $table->integer('pedestrian')->nullable()->comment('piyada eleyhine mina');
            $table->string('type')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
}

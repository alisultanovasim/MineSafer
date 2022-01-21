<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessCategoryLocalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes_category_locales', function (Blueprint $table) {
            $table->uuid("id")->primary()->unique()->index();
            $table->string('name');
            $table->foreignId("processes_category_id")->constrained("processes_categories")->onDelete('cascade');
            $table->string('local', 3)->default('az');
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
        Schema::dropIfExists('processes_category_locales');
    }
}

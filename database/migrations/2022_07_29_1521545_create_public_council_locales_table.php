<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicCouncilLocalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_council_locales', function (Blueprint $table) {
            $table->uuid("id")->primary()->unique()->index();
            $table->string('local', 3);
            $table->foreignId("public_council_id")->constrained("public_councils")->onDelete('cascade');
            $table->longText('text');
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
        Schema::dropIfExists('public_council_locales');
    }
}

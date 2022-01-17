<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeLocales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_locales', function (Blueprint $table) {
            $table->uuid("id")->primary()->unique()->index();
            $table->foreignId("employee_id")->constrained("employees")->nullable()->onDelete('cascade');
            $table->longText('text')->nullable();
            $table->string('full_name');
            $table->string('position_name');
            $table->string('local' , 3)->default('az');
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
        Schema::dropIfExists('employee_locales');
    }
}

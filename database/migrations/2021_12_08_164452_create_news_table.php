<?php

use App\Enums\GalleryType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{

    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid("image_uuid")->constrained("files")->nullable();
            $table->bigInteger('news_category_id')->unsigned();
            $table->integer('category');
            $table->integer('is_active')->default(0);
            $table->integer('position')->default(0);
            $table->timestamp('date');
            $table->timestamps();

            $table->foreign('news_category_id')->references('id')->on('news_categories')->onDelete('cascade');

        });
    }


    public function down()
    {
        Schema::dropIfExists('news');
    }
}

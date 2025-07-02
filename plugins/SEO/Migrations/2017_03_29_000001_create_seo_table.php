<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumInteger('seoable_id', false, true)->index();
            $table->string('seoable_type');
        });
        Schema::create('seo_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->string('title', 70);
            $table->string('description', 160);
            $table->bigInteger('seo_id', false, true)->index();
            $table->foreign('seo_id')->references('id')->on('seo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seo_languages');
        Schema::dropIfExists('seo');
    }
}

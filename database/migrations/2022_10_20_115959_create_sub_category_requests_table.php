<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoryRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_category_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->index()->references('id')->on('categories');
            $table->string('name');
            $table->foreignId('surveyor_id')->index()->references('id')->on('surveyors');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('sub_category_requests');
    }
}

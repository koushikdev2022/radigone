<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyorBlockListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveyor_block_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surveyor_id')->index()->references('id')->on('surveyors');
            $table->foreignId('user_id')->index()->references('id')->on('users');
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
        Schema::dropIfExists('surveyor_block_lists');
    }
}

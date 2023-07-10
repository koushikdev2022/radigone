<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRvToSurveyorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveyors', function (Blueprint $table) {
            $table->tinyInteger('rv')->default(0)->after('sv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surveyors', function (Blueprint $table) {
            $table->dropColumn(['rv']);
        });
    }
}

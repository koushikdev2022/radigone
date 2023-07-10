<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostArrangementToSurveyorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveyors', function (Blueprint $table) {
            $table->tinyInteger('post_arrangement')->default(\App\Surveyor::PA_INACTIVE)->after('total_views');
            $table->tinyInteger('post_arrangement_mode')->default(0)->after('post_arrangement');
            $table->string('post_arrangement_doc')->nullable()->after('post_arrangement_mode');
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
            $table->dropColumn(['post_arrangement', 'post_arrangement_mode', 'post_arrangement_doc']);
        });
    }
}

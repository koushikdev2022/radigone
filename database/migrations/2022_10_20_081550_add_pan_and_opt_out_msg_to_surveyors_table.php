<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPanAndOptOutMsgToSurveyorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveyors', function (Blueprint $table) {
            $table->string('pan')->nullable()->after('designation');
            $table->text('opt_out_msg')->nullable()->after('pan');
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
            $table->dropColumn(['pan', 'opt_out_msg']);
        });
    }
}

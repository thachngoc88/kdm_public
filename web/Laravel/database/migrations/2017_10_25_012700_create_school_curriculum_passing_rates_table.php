<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolCurriculumPassingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_curriculum_passing_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->float('passing_rate',4,3);
            $table->integer('school_id')->unsigned();
            $table->integer('curriculum_id')->unsigned();
            $table->integer('marking_log_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('curriculum_id')->references('id')->on('curriculums');
            $table->foreign('marking_log_id')->references('id')->on('marking_logs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::drop('school_curriculum_passing_rates');
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

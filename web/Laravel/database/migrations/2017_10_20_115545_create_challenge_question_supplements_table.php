<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengeQuestionSupplementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_questions_supplements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('challenge_id')->unsigned();
            $table->integer('question_id')->unsigned();
            $table->integer('supplement_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('challenge_id')->references('id')->on('challenges');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->foreign('supplement_id')->references('id')->on('supplements');
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
        Schema::drop('challenge_questions_supplements');
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

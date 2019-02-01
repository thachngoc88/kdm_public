<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengeUserMapsheetAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_user_mapsheet_accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('challenge_user_id')->unsigned();
            $table->integer('curriculum_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('challenge_user_id')->references('id')->on('challenge_users');
            $table->foreign('curriculum_id')->references('id')->on('curriculums');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challenge_user_mapsheet_accesses');
    }
}

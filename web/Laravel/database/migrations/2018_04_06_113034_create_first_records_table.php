<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirstRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('first_records', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('record')->unsigned()->nullable();
            $table->integer('challenge_user_id')->unsigned();
            $table->integer('question_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            /*Foreign key*/
            $table->foreign('challenge_user_id')->references('id')->on('challenge_users');
            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('first_records');
    }
}

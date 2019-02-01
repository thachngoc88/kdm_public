<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengeUserUnitStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_user_unit_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status');
            $table->integer('challenge_user_id')->unsigned();
            $table->integer('unit_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('challenge_user_id')->references('id')->on('challenge_users');
            $table->foreign('unit_id')->references('id')->on('units');
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
        Schema::drop('challenge_user_unit_statuses');
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengeUserWorkbookStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_user_workbook_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status');
            $table->integer('challenge_user_id')->unsigned();
            $table->integer('workbook_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('challenge_user_id')->references('id')->on('challenge_users');
            $table->foreign('workbook_id')->references('id')->on('workbooks');
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
        Schema::drop('challenge_user_workbook_statuses');
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengeUserWorkbookUpdateCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('challenge_user_workbook_update_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('challenge_user_id')->unsigned();
            $table->integer('workbook_id')->unsigned();
            $table->integer('count');
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
        Schema::dropIfExists('challenge_user_workbook_update_counts');
    }
}

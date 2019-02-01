<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->integer('curriculum_id')->unsigned();
            $table->string('title');
            $table->tinyInteger('order');
            $table->softDeletes();
            $table->timestamps();
            /*Foreign key*/
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
        //DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::drop('timings');
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

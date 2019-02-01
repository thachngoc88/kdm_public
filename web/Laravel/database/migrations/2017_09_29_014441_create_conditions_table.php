<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('timing_id')->unsigned();
            $table->string('condition');
            $table->dateTime('time_from')->nullable();
            $table->dateTime('time_until')->nullable();
            $table->tinyInteger('order');
            $table->softDeletes();
            $table->timestamps();
            /*Foreign key*/
            $table->foreign('timing_id')->references('id')->on('timings');
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
        Schema::drop('conditions');
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

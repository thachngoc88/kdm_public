<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassUnitPassingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_unit_passing_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->float('passing_rate',4,3);
            $table->integer('class_id')->unsigned();
            $table->integer('unit_id')->unsigned();
            $table->integer('marking_log_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('unit_id')->references('id')->on('units');
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
        Schema::dropIfExists('class_unit_passing_rates');
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

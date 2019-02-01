<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('number')->unsigned();
            $table->integer('school_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            /*Foreign key*/
            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('grade_id')->references('id')->on('grades');
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
        Schema::dropIfExists('classes');
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

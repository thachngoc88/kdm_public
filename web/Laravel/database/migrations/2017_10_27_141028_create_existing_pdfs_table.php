<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExistingPdfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('existing_pdfs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->boolean('existing');
            $table->integer('workbook_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
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
        Schema::drop('existing_pdfs');
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

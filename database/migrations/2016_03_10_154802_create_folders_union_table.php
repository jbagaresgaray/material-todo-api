<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoldersUnionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders_union', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('folder_id')->unsigned()->nullable();
            $table->integer('associated_id')->unsigned()->nullable();
            $table->string('department')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('folders_union');
    }
}

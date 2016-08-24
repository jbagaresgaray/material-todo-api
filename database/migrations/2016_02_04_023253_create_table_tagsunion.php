<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTagsunion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags_union', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tag_id')->unsigned()->nullable();
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
        Schema::drop('tags_union');
    }
}

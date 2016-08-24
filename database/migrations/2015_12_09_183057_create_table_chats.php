<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_chat_user_ids')->nullable();
            $table->string('project_group_user_ids')->nullable();
            $table->string('is_one_to_chat')->nullable();
            $table->string('is_project_group_chat')->nullable();
            $table->string('is_group_chat')->nullable();
            $table->string('status_one_to_one')->nullable();
            $table->string('status_group_chat')->nullable();
            $table->string('status_project_group')->nullable();
            $table->integer('user_id_receiver')->unsigned()->nullable();
            $table->integer('user_id_sender')->unsigned()->nullable();
            $table->integer('project_id')->unsigned()->nullable();
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
        Schema::drop('chats');
    }
}

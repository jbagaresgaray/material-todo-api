<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_complete')->default(0);
            $table->boolean('status')->default(0);
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->boolean('starred')->default(0);
            $table->string('priority')->nullable();
            $table->float('estimate_time')->nullable();
            $table->string('time_spent')->nullable();
            $table->integer('parent_task_id')->unsigned()->nullable();
            $table->integer('original_task_id')->unsigned()->nullable();
            $table->integer('project_id')->unsigned()->nullable();
            $table->integer('folder_id')->unsigned()->nullable();
            $table->timestamps();
        });

        /*Schema::table('tasks', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks');
    }
}

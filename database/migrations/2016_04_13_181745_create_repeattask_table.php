<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepeattaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repeat_task', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id')->unsigned()->nullable();
            $table->string('repeat')->nullable();
            $table->date('repeat_from')->nullable();
            $table->boolean('is_group')->default(0);
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
        Schema::drop('repeat_task');
    }
}

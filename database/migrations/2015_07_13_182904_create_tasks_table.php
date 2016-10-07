<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('ad_id');
            $table->integer('user_id');

            $table->float('progress')->default(0);
            $table->integer('result_error')->default(0);
            $table->integer('result_success')->default(0);
            $table->integer('result_total')->default(0);
            $table->enum('status', ['CREATED','PROGRESS','FINISHED'])->nullable();
            $table->enum('type', ['AD_CREATE','AD_UPDATE','AD_REMOVE'])->nullable();

            $table->softDeletes();
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
        Schema::drop('tasks');
    }
}

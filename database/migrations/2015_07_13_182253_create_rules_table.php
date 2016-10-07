<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');

            $table->text('action')->nullable();
            $table->text('condition')->nullable();
            $table->string('interval', 32)->nullable();
            $table->string('name')->nullable();
            $table->string('report_repeated', 32)->nullable();
            $table->string('report_email', 32)->nullable();
            $table->enum('strategy', ['ECONOMIC','BALANCED','AGGRESIVE'])->nullable();

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
        Schema::drop('rules');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuleConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rule_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rule_id');
            $table->integer('condition_id');
            $table->string('comparable')->nullable();
            $table->string('comparison')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rule_conditions');
    }
}

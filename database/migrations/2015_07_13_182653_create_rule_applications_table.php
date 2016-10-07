<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuleApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rule_applications', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('rule_id');

            $table->enum('layer', ['CAMPAIGN','AD','AD_SET'])->nullable();
            $table->string('ref_id', 32)->nullable();

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
        Schema::drop('rule_applications');
    }
}

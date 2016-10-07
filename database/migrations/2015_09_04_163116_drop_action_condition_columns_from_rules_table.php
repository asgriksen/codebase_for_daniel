<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropActionConditionColumnsFromRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rules', function (Blueprint $table)
        {
            $table->dropColumn('action');
            $table->dropColumn('condition');

            $table->enum('layer', ['CAMPAIGNS','ADS','AD_SETS'])->nullable()->after('interval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rules', function (Blueprint $table)
        {
            $table->dropColumn('layer');

            $table->text('action')->nullable();
            $table->text('condition')->nullable();
        });
    }
}

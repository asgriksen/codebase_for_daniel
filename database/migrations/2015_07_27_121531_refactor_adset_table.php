<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorAdsetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_sets', function (Blueprint $table) {
            $table->dropColumn('budget');
            $table->dropColumn('budget_type');
            $table->float('budget_remaining')->default(0);
            $table->float('lifetime_budget')->default(0);
            $table->float('daily_budget')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_sets', function (Blueprint $table) {
            $table->float('budget')->default(0);
            $table->enum('budget_type', ['DAILY', 'LIFETIME'])->nullable();
            $table->dropColumn('budget_remaining');
            $table->dropColumn('lifetime_budget');
            $table->dropColumn('daily_budget');
        });
    }
}

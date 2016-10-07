<?php

use App\AdSet;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToAdSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_sets', function (Blueprint $table)
        {
            $table->float('budget')->default(0);
            $table->enum('budget_type', ['DAILY', 'LIFETIME'])->nullable();
            $table->string('prefix', 32)->nullable();
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
            //
        });
    }
}

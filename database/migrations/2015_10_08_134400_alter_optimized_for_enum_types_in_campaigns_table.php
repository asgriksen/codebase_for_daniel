<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOptimizedForEnumTypesInCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE campaigns CHANGE COLUMN optimize_for optimize_for ENUM('LINK_CLICKS','CLICKS','REACH','IMPRESSIONS') NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE campaigns CHANGE COLUMN optimize_for optimize_for ENUM('CLICKS_TO_WEBSITE','CLICKS','DAILY_UNIQUE_REACH','IMPRESSIONS') NULL");
    }
}

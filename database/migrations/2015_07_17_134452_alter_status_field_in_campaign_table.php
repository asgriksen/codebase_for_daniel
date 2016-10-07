<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStatusFieldInCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('PAUSED','ACTIVE','ARCHIVED', 'DELETED')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('PAUSED','ACTIVE','NOTHING')");
    }
}

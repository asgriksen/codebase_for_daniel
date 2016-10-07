<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStatusFieldInAdsetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE ad_sets MODIFY COLUMN status ENUM('PAUSED','ACTIVE','ARCHIVED', 'DELETED')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("ALTER TABLE ad_sets MODIFY COLUMN status ENUM('PAUSED','ACTIVE')");
    }
}

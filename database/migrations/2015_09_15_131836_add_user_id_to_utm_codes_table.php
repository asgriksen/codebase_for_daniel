<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToUtmCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('utm_codes', function (Blueprint $table)
        {
            $table->integer('user_id')->unsigned()->after('ad_id');
            $table->string('url')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('utm_codes', function (Blueprint $table)
        {
            $table->dropColumn('user_id');
            $table->dropColumn('url');
        });
    }
}

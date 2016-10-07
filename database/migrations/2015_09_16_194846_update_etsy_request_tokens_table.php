<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEtsyRequestTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('etsy_request_tokens', function (Blueprint $table)
        {
            $table->dropColumn('request_token');
            $table->dropColumn('request_secret');

            $table->string('oauth_token_secret')->nullable()->after('oauth_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('etsy_request_tokens', function (Blueprint $table)
        {
            $table->string('request_token')->nullable();
            $table->string('request_secret')->nullable();
        });
    }
}

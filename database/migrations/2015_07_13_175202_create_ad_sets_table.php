<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_sets', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('campaign_id');

            $table->string('name', 100)->nullable();
            $table->enum('status', ['ACTIVE', 'PAUSED'])->nullable();
            $table->enum('type', ['GET_VISITORS_ADS','BOOST_POST_ADS','DYNAMIC_PRODUCT_ADS'])->nullable();

            $table->bigInteger('fb_adset_id')->nullable();
            $table->bigInteger('fb_campaign_id')->nullable();

            $table->softDeletes();
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
        Schema::drop('ad_sets');
    }
}

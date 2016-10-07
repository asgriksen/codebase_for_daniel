<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('account_id');

            $table->float('adset_budget')->default(0);
            $table->enum('adset_budget_type', ['DAILY', 'LIFETIME'])->nullable();
            $table->string('adset_prefix', 32)->nullable();
            $table->float('bidding')->default(0);
            $table->enum('campaign_end', ['PAUSE','DELETE','NOTHING'])->nullable();
            $table->string('conversion_pixel', 32)->nullable();
            $table->bigInteger('fb_campaign_id')->nullable();
            $table->string('name')->nullable();
            $table->enum('optimize_for', ['CLICKS_TO_WEBSITE','CLICKS','DAILY_UNIQUE_REACH','IMPRESSIONS'])->nullable();
            $table->text('schedule');
            $table->enum('schedule_type', ['CONTINUE','START_END','DAYS_OF_WEEK'])->nullable();
            $table->enum('status', ['ACTIVE','PAUSED'])->nullable();


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
        Schema::drop('campaigns');
    }
}

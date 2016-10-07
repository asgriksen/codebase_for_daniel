<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_set_id');

            $table->enum('action_type', ['SHOP_NOW','LEARN_MORE','SIGN_UP','BOOK_NOW','DOWNLOAD'])->nullable();
            $table->float('bid')->default(0);
            $table->float('budget')->default(0);
            $table->text('description')->nullable();
            $table->enum('distribute', ['IMAGE','TITLE','DESCRIPTION','URL'])->nullable();
            $table->string('fb_ad_id')->nullable();
            $table->string('fb_fan_page_id', 1024)->nullable();
            $table->string('facebook_fanpage_link', 1024)->nullable();
            $table->string('facebook_post_link', 1024)->nullable();
            $table->string('facebook_ad_set_link', 1024)->nullable();
            $table->string('name')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['ACTIVE', 'PAUSED'])->nullable();
            $table->tinyInteger('target_desktop')->default(1);
            $table->tinyInteger('target_mobile')->default(1);
            $table->enum('type', ['NEWS_FEED_AD','RIGHT_HAND_SIDE_AD','MULTIPLE_PRODUCTS_AD'])->nullable();
            $table->string('url', 1024)->nullable();
            $table->string('viral_style_campaign_admin_link', 1024)->nullable();
            $table->string('viral_style_product_link', 1024)->nullable();

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
        Schema::drop('ads');
    }
}

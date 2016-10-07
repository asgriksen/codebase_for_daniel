<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdCreativesTable extends Migration {

    /**
     * List of Facebook object types.
     *
     * @var array
     */
    protected $fb_object_types = [
        'APPLICATION',
        'DOMAIN',
        'EVENT',
        'INVALID',
        'OFFER',
        'PAGE',
        'PHOTO',
        'SHARE',
        'STATUS',
        'STORE_ITEM',
        'VIDEO'
    ];

    /**
     * List of Facebook run statuses.
     *
     * @var array
     */
    protected $fb_run_statuses = [
        'ACTIVE',
        'ADGROUP_PAUSED',
        'ARCHIVED',
        'CAMPAIGN_PAUSED',
        'CAMPAIGN_GROUP_PAUSED',
        'DELETED',
        'DISAPPROVED',
        'PAUSED',
        'PENDING',
        'PENDING_BILLING_INFO',
        'PENDING_REVIVEW',
        'PREAPPROVED'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_creatives', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_creatives');
    }
}

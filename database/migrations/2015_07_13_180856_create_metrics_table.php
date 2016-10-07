<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metrics', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('ad_id');

            $table->integer('clicks')->default(0);
            $table->float('cost')->default(0);
            $table->float('cpc')->default(0);
            $table->float('cpi')->default(0);
            $table->float('cpt')->default(0);
            $table->float('ctr')->default(0);
            $table->float('ecommerce_conversion_rate')->default(0);
            $table->integer('fb_comments')->default(0);
            $table->float('fb_conversion_rate')->default(0);
            $table->integer('fb_likes')->default(0);
            $table->integer('fb_shares')->default(0);
            $table->float('frequency')->default(0);
            $table->float('impressions')->default(0);
            $table->float('per_click_value')->default(0);
            $table->float('reach')->default(0);
            $table->float('revenue')->default(0);
            $table->float('roi')->default(0);
            $table->float('spend')->default(0);
            $table->float('transactions')->default(0);


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
        Schema::drop('metrics');
    }
}

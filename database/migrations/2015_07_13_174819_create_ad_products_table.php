<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_products', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('ad_id');
            $table->integer('product_id');

            $table->string('photo')->nullable();
            $table->float('price')->default(0);
            $table->string('title')->nullable();

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
        Schema::drop('ad_products');
    }
}

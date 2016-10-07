<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');

            $table->integer('conversions')->nullable();
            $table->string('name')->nullable();
            $table->string('photo', 512)->nullable();
            $table->float('price')->nullable();
            $table->float('revenue_daily')->nullable();
            $table->float('revenue_monthly')->nullable();
            $table->float('revenue_weekly')->nullable();

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
        Schema::drop('products');
    }
}

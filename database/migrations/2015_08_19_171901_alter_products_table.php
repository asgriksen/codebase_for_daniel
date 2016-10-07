<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table)
        {
            $table->dropColumn('photo');
            $table->dropColumn('user_id');

            $table->integer('cart_id')->unsigned()->after('conversions');

            $table->integer('external_id')
                  ->unsigned()
                  ->nullable()
                  ->after('cart_id')
                  ->comment = 'Refers to the id of the external shopping cart (Viral, Api2Cart, etc.)';

            $table->integer('quantity')
                  ->unsigned()
                  ->nullable()
                  ->default(0)
                  ->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table)
        {
            $table->dropColumn('cart_id');
            $table->dropColumn('external_id');
            $table->dropColumn('quantity');

            $table->integer('user_id')->unsigned();
            $table->string('photo', 255)->nullable();
        });
    }
}

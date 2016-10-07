<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConversionsAndRevenuesToCartCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_categories', function (Blueprint $table) {
            $table->float('conversions')->nullable()->default(0)->after('category_id');
            $table->float('revenues')->nullable()->default(0)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_categories', function (Blueprint $table) {
            $table->dropColumn('conversions');
            $table->dropColumn('revenues');
        });
    }
}

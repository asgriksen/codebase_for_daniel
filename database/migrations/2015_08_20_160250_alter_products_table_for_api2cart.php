<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsTableForApi2cart extends Migration
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
            $table->enum('avail_sale', ['True', 'False'])->nullable();
            $table->json('categories')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_title')->nullable();
            $table->integer('ordered_count')->unsigned()->nullable()->default(0);
            $table->json('product_options')->nullable();
            $table->json('product_variants')->nullable();
            $table->string('short_description')->nullable();
            $table->json('special_price')->nullable();
            $table->string('u_brand')->nullable();
            $table->string('u_model')->nullable();
            $table->string('url')->nullable();
            $table->integer('view_count')->unsigned()->nullable()->default(0);
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
            $table->dropColumn('avail_sale');
            $table->dropColumn('categories');
            $table->dropColumn('description');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_keywords');
            $table->dropColumn('meta_title');
            $table->dropColumn('ordered_count');
            $table->dropColumn('product_options');
            $table->dropColumn('product_variants');
            $table->dropColumn('short_description');
            $table->dropColumn('special_price');
            $table->dropColumn('u_brand');
            $table->dropColumn('u_model');
            $table->dropColumn('url');
            $table->dropColumn('view_count');
        });
    }
}

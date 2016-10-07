<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();

            $table->string('alt')->nullable();
            $table->enum('avail', ['True', 'False'])->nullable();
            $table->timestamp('create_at')->nullable();
            $table->string('file_name')->nullable();
            $table->string('http_path')->nullable();
            $table->string('mime-type')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->integer('size')->unsigned()->nullable()->default(0);
            $table->integer('sort_order')->unsigned()->nullable()->default(0);
            $table->string('type')->nullable();
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
        Schema::drop('product_images');
    }
}

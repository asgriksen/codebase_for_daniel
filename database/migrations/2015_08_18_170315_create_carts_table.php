<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();

            $table->string('AdminAccount')->nullable();
            $table->string('ApiPath')->nullable();
            $table->string('ApiKey')->nullable();
            $table->string('cart_id')->nullable();
            $table->string('store_key')->nullable();
            $table->string('store_url')->nullable();
            $table->enum('verify', ['True', 'False'])->nullable();

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
        Schema::drop('carts');
    }
}

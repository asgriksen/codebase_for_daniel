<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('email')->unique();
            $table->string('name', 64)->nullable();
            $table->timestamp('online_check_at')->nullable();
            $table->string('password', 60);
            $table->enum('status', ['ACTIVE', 'BLOCKED', 'UNVERIFIED'])->default('UNVERIFIED');
            $table->enum('type', ['ADMIN', 'USER'])->default('USER');
            $table->string('verify_token')->nullable();
            $table->timestamp('verify_token_expiry')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->rememberToken();
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
        Schema::drop('users');
    }
}

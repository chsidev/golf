<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 150)->unique();
            $table->string('avatar', 100)->nullable();
            $table->string('password', 100);
            $table->string('password_reset_token', 100)->nullable();
            $table->foreignId('role_id');
            $table->boolean('active')->default(true);
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};

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
            $table->string('email')->unique();
            $table->string('password');
            $table->string('line_user_id')->unique();
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->string('role');
            $table->boolean('is_delete')->default(0);
            $table->boolean('is_block')->default(0);
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->integer('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('token_verify_email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};

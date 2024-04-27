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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_manager_id');
            $table->string('channel_id')->nullable();
            $table->string('channel_name')->nullable();
            $table->string('channel_secret')->nullable();
            $table->string('channel_access_token')->nullable();
            $table->string('picture_url')->nullable();
            $table->timestamps();

            $table->foreign('account_manager_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
};

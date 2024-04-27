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
        Schema::create('channel_statisticals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id');
            $table->date('date');
            $table->integer('api_broadcast')->default(0)->nullable();
            $table->integer('api_multicast')->default(0)->nullable();
            $table->integer('followers')->default(0)->nullable();
            $table->integer('targeted_reaches')->default(0)->nullable();
            $table->integer('blocks')->default(0)->nullable();
            $table->timestamps();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_statisticals');
    }
};

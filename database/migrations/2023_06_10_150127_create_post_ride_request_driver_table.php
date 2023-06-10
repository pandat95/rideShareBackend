<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_ride_request_driver', function (Blueprint $table) {
            $table->unsignedBigInteger('post_ride_request_id');
            $table->unsignedBigInteger('driver_id');
            

            $table->foreign('post_ride_request_id')->references('id')->on('post_ride_request')->onDelete('cascade');
            $table->foreign('driver_id')->references('stu_id')->on('student')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_ride_request_driver');
    }
};

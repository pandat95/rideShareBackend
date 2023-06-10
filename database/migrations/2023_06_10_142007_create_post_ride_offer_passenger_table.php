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
        Schema::create('post_ride_offer_passenger', function (Blueprint $table) {
            $table->unsignedBigInteger('post_ride_offer_id');
            $table->unsignedBigInteger('passenger_id');

            $table->foreign('post_ride_offer_id')->references('id')->on('post_ride_offer')->onDelete('cascade');
            $table->foreign('passenger_id')->references('stu_id')->on('student')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_ride_offer_passenger');
    }
};

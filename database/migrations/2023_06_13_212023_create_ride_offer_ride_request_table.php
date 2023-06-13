<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('ride_offer_ride_request', function (Blueprint $table) {
        $table->unsignedBigInteger('ride_offer_id');
        $table->unsignedBigInteger('ride_request_id');
        

        $table->foreign('ride_offer_id')->references('id')->on('ride_offer')->onDelete('cascade');
        $table->foreign('ride_request_id')->references('id')->on('ride_request')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_offer_ride_request');
    }
};

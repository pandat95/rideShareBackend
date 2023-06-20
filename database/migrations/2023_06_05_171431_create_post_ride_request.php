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
        Schema::create('post_ride_request', function (Blueprint $table) {
            $table->id();
            $table->string('title')->notNullable();
            $table->string('Subtitle')->notNullable();
            $table->timestamps();
            // $table->date('date')->notNullable();
            // $table->time('time')->notNullable();
            $table->dateTime('DateTime')->notNullable();
            $table->boolean('smoking')->notNullable();
            $table->boolean('eating')->notNullable();
            $table->string('pickup_loc_latitude')->notNullable();
            $table->string('pickup_loc_longitude')->notNullable();
            $table->string('destination_latitude')->notNullable();
            $table->string('destination_longitude')->notNullable();
            $table->string('driver_gender')->notNullable();
            $table->unsignedBigInteger('studentID')->nullable()->default(0);
            $table->foreign('studentID')->references('stu_id')->on('student');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_ride_request');
    }
};

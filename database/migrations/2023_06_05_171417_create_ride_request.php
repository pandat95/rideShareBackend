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
        Schema::create('ride_request', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('smoking')->notNullable();
            $table->boolean('eating')->notNullable();
            $table->string('pickup_loc_latitude')->notNullable();
            $table->string('pickup_loc_longitude')->notNullable();
            $table->string('destination_latitude')->notNullable();
            $table->string('destination_longitude')->notNullable();
            $table->unsignedInteger('driver_gender')->notNullable();
            $table->unsignedBigInteger('studentID');
            $table->foreign('studentID')->references('stu_id')->on('student')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_request');
    }
};

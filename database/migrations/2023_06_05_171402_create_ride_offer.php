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
        Schema::create('ride_offer', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('smoking')->notNullable();
            $table->boolean('eating')->notNullable();
            $table->float('pickup_loc_latitude')->notNullable();
            $table->float('pickup_loc_longitude')->notNullable();
            $table->float('destination_latitude')->notNullable();
            $table->float('destination_longitude')->notNullable();
            $table->unsignedInteger('passenger_gender')->notNullable();
            $table->string('manufacturer')->notNullable();
            $table->string('model')->notNullable();
            $table->string('color')->notNullable();
            $table->string('plates_number')->notNullable();
            $table->unsignedBigInteger('studentID');
            $table->foreign('studentID')->references('stu_id')->on('student')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_offer');
    }
};

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
        Schema::create('ride_offer_student', function (Blueprint $table) {
            $table->unsignedBigInteger('ride_offer_id');
            $table->unsignedBigInteger('student_id');
            // Additional columns if needed
            $table->timestamps();

            $table->foreign('ride_offer_id')->references('id')->on('ride_offer')->onDelete('cascade');
            $table->foreign('student_id')->references('stu_id')->on('student')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_offer_student');
    }
};

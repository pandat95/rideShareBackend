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
        Schema::create('ride_request_student', function (Blueprint $table) {
            $table->unsignedBigInteger('ride_request_id');
            $table->unsignedBigInteger('student_id');
            // Additional columns if needed
            $table->timestamps();

            $table->foreign('ride_request_id')->references('id')->on('ride_request')->onDelete('cascade');
            $table->foreign('student_id')->references('stu_id')->on('student')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_request_student');
    }
};

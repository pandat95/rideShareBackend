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
            $table->decimal('pickup_loc_latitude',15,12)->notNullable();
            $table->decimal('pickup_loc_longitude',15,12)->notNullable();
            $table->decimal('destination_latitude',15,12)->notNullable();
            $table->decimal('destination_longitude',15,12)->notNullable();
            $table->string('driver_gender')->notNullable();
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

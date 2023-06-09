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
        Schema::create('student', function (Blueprint $table) {
            $table->unsignedBigInteger('stu_id')->primary();
            $table->string('first_name')->notNullable();
            $table->string('last_name')->notNullable();
            $table->string('email')->unique()->notNullable();
            $table->boolean('gender')->notNullable();
            $table->string('password')->notNullable();
            $table->rememberToken();
            $table->string('phone')->notNullable();
            $table->string('address')->default('');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('api_token',80)->uniqid()->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};

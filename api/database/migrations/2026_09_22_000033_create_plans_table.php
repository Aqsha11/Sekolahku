<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug', 64)->unique();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->string('billing_interval', 16)->default('MONTHLY');
            $table->unsignedInteger('student_limit')->nullable();
            $table->unsignedInteger('teacher_limit')->nullable();
            $table->unsignedBigInteger('storage_limit')->nullable();
            $table->json('features')->nullable();
            $table->string('status', 32)->default('ACTIVE')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
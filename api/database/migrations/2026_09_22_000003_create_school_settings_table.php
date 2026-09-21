<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('school_id')->constrained()->cascadeOnDelete();
            $table->string('timezone', 64)->default('Asia/Makassar');
            $table->string('currency', 8)->default('IDR');
            $table->integer('attendance_late_threshold')->default(15);
            $table->time('attendance_start_time')->nullable();
            $table->time('attendance_end_time')->nullable();
            $table->boolean('enable_parent_notification')->default(true);
            $table->boolean('enable_teacher_notification')->default(true);
            $table->boolean('enable_whatsapp')->default(false);
            $table->boolean('enable_email')->default(false);
            $table->boolean('enable_push')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
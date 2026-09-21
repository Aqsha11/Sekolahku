<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_attendance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lesson_session_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('student_id')->constrained()->cascadeOnDelete();
            $table->string('status', 32)->default('PRESENT');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['lesson_session_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_attendance');
    }
};
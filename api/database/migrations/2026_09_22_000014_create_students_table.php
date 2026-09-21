<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('school_id')->constrained()->cascadeOnDelete();
            $table->string('nis', 30)->index();
            $table->string('nisn', 20)->nullable()->index();
            $table->string('name');
            $table->string('gender', 16)->default('MALE')->index();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('religion', 32)->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->uuid('photo_file_id')->nullable()->index();
            $table->string('status', 32)->default('ACTIVE')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_id', 'nis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
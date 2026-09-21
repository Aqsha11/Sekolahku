<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('school_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_number')->nullable()->index();
            $table->string('nip', 30)->nullable();
            $table->string('name');
            $table->string('gender', 16)->default('MALE')->index();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->uuid('photo_file_id')->nullable()->index();
            $table->string('status', 32)->default('ACTIVE')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_id', 'employee_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
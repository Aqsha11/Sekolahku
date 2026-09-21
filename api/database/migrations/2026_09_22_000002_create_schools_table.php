<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->restrictOnDelete();
            $table->string('school_code')->index();
            $table->string('npsn', 20)->nullable()->index();
            $table->string('name');
            $table->string('level', 32)->index();
            $table->text('address')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('village')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->uuid('logo_file_id')->nullable()->index();
            $table->string('status', 32)->default('ACTIVE')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'school_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
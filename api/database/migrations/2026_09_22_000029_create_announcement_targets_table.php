<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_targets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('announcement_id')->constrained()->cascadeOnDelete();
            $table->string('target_type', 32)->default('ALL');
            $table->uuid('target_id')->nullable()->index();
            $table->timestamps();

            $table->index(['announcement_id', 'target_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_targets');
    }
};
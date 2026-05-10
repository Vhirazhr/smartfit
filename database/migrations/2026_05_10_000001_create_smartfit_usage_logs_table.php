<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smartfit_usage_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('flow_type', 20)->index();
            $table->foreignId('body_measurement_id')->nullable()->constrained('body_measurements')->nullOnDelete();
            $table->string('body_type', 100)->nullable();
            $table->string('morphotype', 60)->nullable()->index();
            $table->string('style_preference', 50)->nullable()->index();
            $table->string('color_tone', 50)->nullable();
            $table->decimal('bust', 6, 2)->nullable();
            $table->decimal('waist', 6, 2)->nullable();
            $table->decimal('hip', 6, 2)->nullable();
            $table->decimal('bust_to_waist_ratio', 7, 4)->nullable();
            $table->decimal('hip_to_waist_ratio', 7, 4)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('country_code', 2)->nullable()->index();
            $table->string('country_name', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->index(['flow_type', 'created_at']);
            $table->index(['country_code', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smartfit_usage_logs');
    }
};

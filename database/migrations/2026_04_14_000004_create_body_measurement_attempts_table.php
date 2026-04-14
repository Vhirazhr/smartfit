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
        Schema::create('body_measurement_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('body_measurement_id')->nullable()->constrained('body_measurements')->nullOnDelete();
            $table->decimal('bust', 6, 2)->nullable();
            $table->decimal('waist', 6, 2)->nullable();
            $table->decimal('hip', 6, 2)->nullable();
            $table->string('status', 20)->index();
            $table->json('rejection_reasons')->nullable();
            $table->boolean('is_consistency_issue')->default(false)->index();
            $table->string('measurement_standard', 50)->default('ISO 8559-1');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('body_measurement_attempts');
    }
};

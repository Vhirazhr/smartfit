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
        Schema::create('body_measurements', function (Blueprint $table) {
            $table->id();
            $table->decimal('bust', 6, 2);
            $table->decimal('waist', 6, 2);
            $table->decimal('hip', 6, 2);
            $table->decimal('bust_to_waist_ratio', 7, 4);
            $table->decimal('hip_to_waist_ratio', 7, 4);
            $table->string('morphotype', 60)->index();
            $table->string('morphotype_label', 100);
            $table->string('measurement_standard', 50)->default('ISO 8559-1');
            $table->string('source', 100)->nullable();
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
        Schema::dropIfExists('body_measurements');
    }
};

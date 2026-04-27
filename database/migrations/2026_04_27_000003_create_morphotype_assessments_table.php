<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('morphotype_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('body_measurement_id')->constrained('body_measurements')->cascadeOnDelete();
            $table->string('assessor_code', 50)->index();
            $table->string('assessed_morphotype', 50)->index();
            $table->string('predicted_morphotype', 50)->nullable()->index();
            $table->decimal('bw_ratio', 7, 4)->nullable();
            $table->decimal('hw_ratio', 7, 4)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('assessed_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['body_measurement_id', 'assessor_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('morphotype_assessments');
    }
};

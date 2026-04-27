<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('morphotype_evaluation_runs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('dataset_label', 120)->nullable();
            $table->unsignedInteger('total_samples');
            $table->unsignedInteger('agreements');
            $table->decimal('agreement_percentage', 5, 2);
            $table->json('confusion_matrix');
            $table->decimal('baseline_percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('evaluated_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('morphotype_evaluation_runs');
    }
};

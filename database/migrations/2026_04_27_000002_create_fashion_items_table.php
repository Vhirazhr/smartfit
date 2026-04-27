<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fashion_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fashion_category_id')->constrained('fashion_categories')->restrictOnDelete();
            $table->string('title', 150)->index();
            $table->text('description')->nullable();
            $table->string('body_type', 50)->index();
            $table->string('image_source', 10)->index();
            $table->string('image_path')->nullable();
            $table->string('image_url', 2048)->nullable();
            $table->string('purchase_link', 2048)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();

            $table->index(['fashion_category_id', 'is_active', 'sort_order']);
            $table->index(['body_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fashion_items');
    }
};

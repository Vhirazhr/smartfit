<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fashion_items', function (Blueprint $table): void {
            $table->string('style_preference', 50)->nullable()->after('body_type')->index();
            $table->string('color_tone', 50)->nullable()->after('style_preference');
        });

        Schema::create('fashion_item_stores', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('fashion_item_id')->constrained('fashion_items')->cascadeOnDelete();
            $table->string('store_name', 120);
            $table->string('store_link', 2048)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['fashion_item_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fashion_item_stores');

        Schema::table('fashion_items', function (Blueprint $table): void {
            $table->dropIndex(['style_preference']);
            $table->dropColumn(['style_preference', 'color_tone']);
        });
    }
};

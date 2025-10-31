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
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained('themes')->onDelete('cascade');
            $table->string('key'); // e.g., 'hero_title', 'hero_subtitle', 'service_1_title'
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, url, number
            $table->string('group')->nullable(); // e.g., 'hero', 'services', 'features'
            $table->integer('order')->default(0);
            $table->timestamps();

            // Ensure unique key per theme
            $table->unique(['theme_id', 'key']);
            $table->index('theme_id');
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};

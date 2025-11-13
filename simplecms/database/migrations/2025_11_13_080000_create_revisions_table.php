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
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->string('revisionable_type'); // Post, Page, etc.
            $table->unsignedBigInteger('revisionable_id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('version')->default(1);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['revisionable_type', 'revisionable_id']);
            $table->index('user_id');
            $table->index('version');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};

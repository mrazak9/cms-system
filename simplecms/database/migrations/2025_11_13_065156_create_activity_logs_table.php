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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            $table->string('log_type'); // 'create', 'update', 'delete', 'login', 'logout', 'restore'
            $table->string('subject_type')->nullable(); // Model class name (Post, Page, etc)
            $table->unsignedBigInteger('subject_id')->nullable(); // Model ID

            $table->string('description');
            $table->json('properties')->nullable(); // Store old/new values

            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('log_type');
            $table->index('subject_type');
            $table->index('subject_id');
            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

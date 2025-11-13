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
        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('scheduled_publish_at')->nullable()->after('published_at');
            $table->enum('workflow_status', ['draft', 'pending_review', 'scheduled', 'published', 'archived'])
                  ->default('draft')->after('scheduled_publish_at');

            $table->index('scheduled_publish_at');
            $table->index('workflow_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['scheduled_publish_at']);
            $table->dropIndex(['workflow_status']);
            $table->dropColumn(['scheduled_publish_at', 'workflow_status']);
        });
    }
};

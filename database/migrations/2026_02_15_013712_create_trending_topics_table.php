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
        Schema::create('trending_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->constrained('topic_sources')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->integer('score')->default(0);
            $table->integer('upvotes')->nullable();
            $table->integer('comments')->nullable();
            $table->string('category')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_used')->default(false);
            $table->timestamp('fetched_at');
            $table->timestamps();

            $table->index(['score', 'is_used']);
            $table->index('fetched_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trending_topics');
    }
};

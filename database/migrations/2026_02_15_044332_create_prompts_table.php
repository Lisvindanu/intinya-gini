<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // tldr_v1, tldr_drama, tldr_tech
            $table->string('description')->nullable();
            $table->text('system_prompt');
            $table->text('user_prompt_template');
            $table->json('config')->nullable(); // temperature, max_tokens, etc
            $table->boolean('is_active')->default(true);
            $table->integer('version')->default(1);
            $table->timestamps();
            
            $table->index(['is_active', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompts');
    }
};

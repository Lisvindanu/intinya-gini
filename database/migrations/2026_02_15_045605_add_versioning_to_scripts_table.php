<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scripts', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_script_id')->nullable()->after('topic_id');
            $table->integer('version')->default(1)->after('parent_script_id');
            $table->enum('variation_type', ['original', 'regenerate', 'hook_variation', 'tone_variation'])->default('original')->after('version');
            $table->json('generation_config')->nullable()->after('variation_type');
            
            $table->foreign('parent_script_id')->references('id')->on('scripts')->onDelete('cascade');
            $table->index(['parent_script_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::table('scripts', function (Blueprint $table) {
            $table->dropForeign(['parent_script_id']);
            $table->dropIndex(['parent_script_id', 'version']);
            $table->dropColumn(['parent_script_id', 'version', 'variation_type', 'generation_config']);
        });
    }
};

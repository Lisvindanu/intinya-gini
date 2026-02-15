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
        Schema::table('topics', function (Blueprint $table) {
            $table->foreignId('trending_topic_id')->nullable()->after('duration')->constrained('trending_topics')->nullOnDelete();
            $table->string('source_type')->nullable()->after('trending_topic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropForeign(['trending_topic_id']);
            $table->dropColumn(['trending_topic_id', 'source_type']);
        });
    }
};

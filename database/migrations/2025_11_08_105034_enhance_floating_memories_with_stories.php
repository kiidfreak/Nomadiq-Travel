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
        Schema::table('floating_memories', function (Blueprint $table) {
            $table->string('traveler_name', 100)->nullable()->after('caption');
            $table->text('story')->nullable()->after('traveler_name'); // Rich story text
            $table->boolean('is_traveler_of_month')->default(false)->after('story');
            $table->string('video_url', 500)->nullable()->after('image_url'); // For video memories
            $table->string('media_type')->default('image')->after('video_url'); // 'image' or 'video'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('floating_memories', function (Blueprint $table) {
            $table->dropColumn(['traveler_name', 'story', 'is_traveler_of_month', 'video_url', 'media_type']);
        });
    }
};

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
        Schema::table('packages', function (Blueprint $table) {
            $table->string('theme', 100)->nullable()->after('title');
            $table->string('tagline', 200)->nullable()->after('theme');
            $table->json('highlights')->nullable()->after('description'); // Array of highlight items with emoji and text
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['theme', 'tagline', 'highlights']);
        });
    }
};

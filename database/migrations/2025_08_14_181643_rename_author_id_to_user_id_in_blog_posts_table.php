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
        // Check if author_id column exists before renaming
        if (Schema::hasColumn('blog_posts', 'author_id')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->renameColumn('author_id', 'user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if user_id column exists before renaming
        if (Schema::hasColumn('blog_posts', 'user_id') && !Schema::hasColumn('blog_posts', 'author_id')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->renameColumn('user_id', 'author_id');
            });
        }
    }
};

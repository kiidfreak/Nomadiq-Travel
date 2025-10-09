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
            $table->integer('slot')->nullable()->before('is_published')->comment('Position slot for website display (1-10)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('floating_memories', function (Blueprint $table) {
            $table->dropColumn('slot');
        });
    }
};

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
        Schema::create('micro_experiences', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('emoji', 10)->nullable(); // Emoji icon
            $table->string('category', 50); // wellness, culture, adventure, nature, food
            $table->text('description');
            $table->decimal('price_usd', 10, 2)->nullable(); // Optional price for add-on
            $table->integer('duration_hours')->nullable(); // Duration in hours
            $table->string('location', 200)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->json('available_packages')->nullable(); // Array of package IDs this experience can be added to
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('micro_experiences');
    }
};

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
        Schema::create('package_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->integer('day_number');
            $table->string('title', 200);
            $table->text('description');
            $table->string('accommodation', 200)->nullable();
            $table->string('meals_included', 100)->nullable();
            $table->text('activities')->nullable();
            $table->timestamps();

            // Ensure unique day numbers per package
            $table->unique(['package_id', 'day_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_itineraries');
    }
};

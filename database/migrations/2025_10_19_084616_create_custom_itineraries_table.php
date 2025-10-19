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
        Schema::create('custom_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->text('special_requests')->nullable();
            $table->enum('status', ['pending_approval', 'approved', 'rejected', 'needs_revision'])->default('pending_approval');
            $table->text('admin_notes')->nullable();
            $table->string('reference_id');
            $table->timestamp('submitted_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Day-by-day itinerary fields (similar to PackageItinerary)
            $table->integer('day_number');
            $table->string('title');
            $table->text('description');
            $table->string('accommodation_preference')->nullable();
            $table->string('meals_preference')->nullable();
            $table->text('activities_preference')->nullable();
            $table->text('special_notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['customer_email', 'package_id']);
            $table->index(['reference_id', 'day_number']);
            $table->index('status');
            $table->index('submitted_at');
            $table->unique(['reference_id', 'day_number']); // Prevent duplicate days per submission
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_itineraries');
    }
};

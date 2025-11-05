<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            
            // Contact Information
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('country');
            
            // Trip Details
            $table->json('destinations'); // Multiple selections
            $table->date('travel_start_date')->nullable();
            $table->date('travel_end_date')->nullable();
            $table->string('duration')->nullable(); // 3-5, 6-8, etc.
            $table->unsignedTinyInteger('adults')->default(2);
            $table->unsignedTinyInteger('children')->default(0);
            $table->string('children_ages')->nullable();
            
            // Accommodation
            $table->json('accommodation_types'); // Multiple selections
            $table->string('room_configuration')->nullable();
            
            // Activities & Interests
            $table->json('activities')->nullable(); // Selected activities
            $table->json('special_interests')->nullable(); // honeymoon, photography, etc.
            $table->json('wildlife_preferences')->nullable();
            
            // Budget & Additional Info
            $table->string('budget_range')->nullable();
            $table->text('dietary_requirements')->nullable();
            $table->text('mobility_considerations')->nullable();
            $table->text('additional_requests')->nullable();
            
            // Admin Management Fields
            $table->enum('status', [
                'new',
                'reviewing',
                'quoted',
                'negotiating',
                'accepted',
                'rejected',
                'expired'
            ])->default('new');
            
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Admin Notes & Response
            $table->text('admin_notes')->nullable();
            $table->text('proposal_response')->nullable();
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->string('quoted_currency', 3)->default('USD');
            
            // Tracking
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            
            // Source tracking
            $table->string('source')->default('website'); // website, referral, etc.
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('status');
            $table->index('email');
            $table->index('created_at');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
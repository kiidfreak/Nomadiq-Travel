<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 100);
            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('set null');
            $table->integer('rating')->unsigned();
            $table->text('review_text')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            
            // Add indexes
            $table->index(['is_published', 'rating']);
            $table->index('created_at');
        });

        // Add check constraint for rating using raw SQL
        DB::statement('ALTER TABLE testimonials ADD CONSTRAINT chk_rating CHECK (rating >= 1 AND rating <= 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};

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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, number, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default currency conversion rate (1 USD = ~140 KSh)
        if (!\Illuminate\Support\Facades\DB::table('settings')->where('key', 'usd_to_ksh_rate')->exists()) {
            \Illuminate\Support\Facades\DB::table('settings')->insert([
                'key' => 'usd_to_ksh_rate',
                'value' => '140',
                'type' => 'number',
                'description' => 'USD to Kenyan Shilling conversion rate',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};


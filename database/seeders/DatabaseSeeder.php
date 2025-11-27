<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Destination;
use App\Models\Package;
use App\Models\Testimonial;
use App\Models\FloatingMemory;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if data is already seeded
        if (User::where('email', 'admin@nomadiq.com')->exists()) {
            $this->command->info('Database already seeded. Skipping...');
            return;
        }

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@nomadiq.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Blogger User
        $blogger = User::create([
            'name' => 'Blog Writer',
            'email' => 'blogger@nomadiq.com',
            'password' => Hash::make('password'),
            'role' => 'blogger',
        ]);

        // Create Destinations
        $watamu = Destination::create([
            'name' => 'Watamu',
            'description' => 'A pristine coastal paradise with white sandy beaches, crystal-clear waters, and vibrant marine life. Perfect for snorkeling, diving, and beach relaxation.',
            'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800',
            'is_active' => true,
        ]);

        $malindi = Destination::create([
            'name' => 'Malindi',
            'description' => 'Historic coastal town with rich Swahili culture, ancient ruins, and beautiful beaches. Explore the Gedi Ruins and enjoy the local cuisine.',
            'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800',
            'is_active' => true,
        ]);

        $lamu = Destination::create([
            'name' => 'Lamu',
            'description' => 'UNESCO World Heritage site with narrow streets, traditional architecture, and a rich cultural heritage. Experience authentic Swahili culture.',
            'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
            'is_active' => true,
        ]);

        // Create Packages
        $weekendBash = Package::create([
            'title' => 'Weekend Bash - 2 Nights / 1 Day',
            'description' => 'Experience the perfect coastal getaway with a villa stay, sunset dhow ride, beach party at Papa Remo, and sand dunes excursion. Includes half-meal plan.',
            'duration_days' => 2,
            'price_usd' => 250.00,
            'max_participants' => 8,
            'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800',
            'is_active' => true,
        ]);
        $weekendBash->destinations()->attach([$watamu->id]);

        $explorerWeekend = Package::create([
            'title' => 'Explorer Weekend - 3 Days / 2 Nights',
            'description' => 'Discover the best of coastal Kenya with villa accommodation, Safari Blu or Dhow ride, Gedi Ruins & Malindi Museum tour, and sand dunes & Hells Kitchen exploration.',
            'duration_days' => 3,
            'price_usd' => 450.00,
            'max_participants' => 6,
            'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
            'is_active' => true,
        ]);
        $explorerWeekend->destinations()->attach([$watamu->id, $malindi->id]);

        $lamuExperience = Package::create([
            'title' => 'Lamu Cultural Experience - 4 Days / 3 Nights',
            'description' => 'Immerse yourself in the rich Swahili culture of Lamu. Explore the historic old town, visit local artisans, enjoy traditional dhow sailing, and experience authentic coastal cuisine.',
            'duration_days' => 4,
            'price_usd' => 650.00,
            'max_participants' => 10,
            'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800',
            'is_active' => true,
        ]);
        $lamuExperience->destinations()->attach([$lamu->id]);

        // Create Testimonials
        Testimonial::create([
            'customer_name' => 'Sarah Johnson',
            'package_id' => $weekendBash->id,
            'rating' => 5,
            'review_text' => 'Absolutely amazing experience! The weekend bash was everything we hoped for and more. The sunset dhow ride was magical, and the beach party was unforgettable. Highly recommend!',
            'is_published' => true,
        ]);

        Testimonial::create([
            'customer_name' => 'Michael Chen',
            'package_id' => $explorerWeekend->id,
            'rating' => 5,
            'review_text' => 'The Explorer Weekend exceeded all expectations. The Gedi Ruins tour was fascinating, and our guide was incredibly knowledgeable. The villa accommodation was luxurious and comfortable.',
            'is_published' => true,
        ]);

        Testimonial::create([
            'customer_name' => 'Emma Williams',
            'package_id' => $lamuExperience->id,
            'rating' => 5,
            'review_text' => 'Lamu is a hidden gem! The cultural experience was authentic and eye-opening. The local food was delicious, and the people were so welcoming. This trip will stay with me forever.',
            'is_published' => true,
        ]);

        Testimonial::create([
            'customer_name' => 'David Brown',
            'package_id' => $weekendBash->id,
            'rating' => 4,
            'review_text' => 'Great value for money! The weekend bash was well-organized and fun. The sand dunes excursion was a highlight. Would definitely book again.',
            'is_published' => true,
        ]);

        Testimonial::create([
            'customer_name' => 'Lisa Anderson',
            'package_id' => $explorerWeekend->id,
            'rating' => 5,
            'review_text' => 'Perfect blend of adventure and relaxation. The Safari Blu experience was incredible, and Hells Kitchen was breathtaking. Nomadiq knows how to create memorable experiences!',
            'is_published' => true,
        ]);

        // Create Floating Memories
        FloatingMemory::create([
            'destination_id' => $watamu->id,
            'package_id' => $weekendBash->id,
            'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800',
            'caption' => 'Sunset dhow ride in Watamu - a magical evening on the Indian Ocean',
            'safari_date' => now()->subDays(15),
            'slot' => 1,
            'is_published' => true,
        ]);

        FloatingMemory::create([
            'destination_id' => $malindi->id,
            'package_id' => $explorerWeekend->id,
            'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
            'caption' => 'Exploring the ancient Gedi Ruins - a journey through history',
            'safari_date' => now()->subDays(30),
            'slot' => 2,
            'is_published' => true,
        ]);

        FloatingMemory::create([
            'destination_id' => $lamu->id,
            'package_id' => $lamuExperience->id,
            'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800',
            'caption' => 'Traditional dhow sailing in Lamu - experiencing authentic Swahili culture',
            'safari_date' => now()->subDays(45),
            'slot' => 3,
            'is_published' => true,
        ]);

        // Create Blog Categories
        $travelTips = BlogCategory::create([
            'name' => 'Travel Tips',
            'slug' => 'travel-tips',
            'description' => 'Essential tips and advice for your coastal adventure',
            'is_active' => true,
        ]);

        $destinations = BlogCategory::create([
            'name' => 'Destinations',
            'slug' => 'destinations',
            'description' => 'Explore our beautiful coastal destinations',
            'is_active' => true,
        ]);

        $experiences = BlogCategory::create([
            'name' => 'Experiences',
            'slug' => 'experiences',
            'description' => 'Stories and guides from our travel experiences',
            'is_active' => true,
        ]);

        // Create Blog Posts
        BlogPost::create([
            'title' => 'Top 10 Things to Do in Watamu',
            'slug' => 'top-10-things-to-do-in-watamu',
            'content' => 'Watamu is a coastal paradise offering incredible experiences. From snorkeling in the marine park to exploring the Gede Ruins, here are the top 10 must-do activities in Watamu.',
            'featured_image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800',
            'category_id' => $destinations->id,
            'package_id' => $weekendBash->id,
            'destination_id' => $watamu->id,
            'user_id' => $blogger->id,
            'is_published' => true,
            'published_at' => now()->subDays(5),
            'meta_description' => 'Discover the best activities and experiences in Watamu, Kenya\'s coastal paradise.',
        ]);

        BlogPost::create([
            'title' => 'A Complete Guide to Lamu Island',
            'slug' => 'complete-guide-to-lamu-island',
            'content' => 'Lamu Island is a UNESCO World Heritage site that offers a unique glimpse into Swahili culture. This comprehensive guide covers everything you need to know about visiting Lamu.',
            'featured_image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
            'category_id' => $destinations->id,
            'package_id' => $lamuExperience->id,
            'destination_id' => $lamu->id,
            'user_id' => $blogger->id,
            'is_published' => true,
            'published_at' => now()->subDays(10),
            'meta_description' => 'Your complete guide to exploring Lamu Island, Kenya\'s cultural gem.',
        ]);

        BlogPost::create([
            'title' => 'What to Pack for Your Coastal Adventure',
            'slug' => 'what-to-pack-for-coastal-adventure',
            'content' => 'Packing for a coastal adventure in Kenya requires some planning. Here\'s a comprehensive packing list to ensure you\'re prepared for sun, sand, and sea.',
            'featured_image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800',
            'category_id' => $travelTips->id,
            'user_id' => $blogger->id,
            'is_published' => true,
            'published_at' => now()->subDays(3),
            'meta_description' => 'Essential packing tips for your coastal adventure in Kenya.',
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📧 Admin Login: admin@nomadiq.com / password: password');
        $this->command->info('📧 Blogger Login: blogger@nomadiq.com / password: password');
    }
}

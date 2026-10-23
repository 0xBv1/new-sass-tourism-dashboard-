<?php

namespace Database\Seeders;

use App\Enums\BlogStatus;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\Category;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Destination;
use App\Models\Duration;
use App\Models\Faq;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\Room;
use App\Models\Tour;
use App\Models\TourReview;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FrontendTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Frontend Test Data Seeder...');
        
        // Get admin user for published_by
        $admin = User::where('email', 'admin@admin.com')->first();
        if (!$admin) {
            $admin = User::first();
        }

        // 1. Create Blogs with Categories and Comments
        $this->command->info('Creating blogs with comments...');
        $this->createBlogsWithComments($admin);

        // 2. Create Hotels with Rooms
        $this->command->info('Creating hotels with rooms...');
        $this->createHotelsWithRooms();

        // 3. Create Tour Reviews
        $this->command->info('Creating tour reviews...');
        $this->createTourReviews();

        // 4. Create Clients
        $this->command->info('Creating test clients...');
        $this->createClients();

        // 5. Create FAQs
        $this->command->info('Creating FAQs...');
        $this->createFAQs();

        $this->command->info('Frontend Test Data Seeder completed successfully!');
    }

    private function createBlogsWithComments($admin): void
    {
        $blogCategories = BlogCategory::all();
        if ($blogCategories->isEmpty()) {
            $this->command->warn('No blog categories found. Please run BlogCategoriesSeeder first.');
            return;
        }

        $blogTitles = [
            '10 Must-Visit Destinations in Egypt',
            'Complete Guide to Egyptian Pyramids',
            'Best Time to Visit Egypt: Weather and Seasons',
            'Nile Cruise Experience: What to Expect',
            'Egyptian Cuisine: A Food Lover\'s Guide',
            'Luxor and Karnak Temples: Ancient Wonders',
            'Red Sea Diving: Best Spots and Tips',
            'Cairo Nightlife: Where to Go',
            'Egyptian Culture and Traditions',
            'Budget Travel Tips for Egypt',
            'Luxury Hotels in Egypt: Top Recommendations',
            'Sahara Desert Adventure: Complete Guide',
            'Egyptian Museums You Must Visit',
            'Shopping in Egypt: Best Markets and Souvenirs',
            'Family-Friendly Activities in Egypt',
        ];

        $blogDescriptions = [
            '<p>Egypt is a land of ancient wonders and modern marvels. From the iconic pyramids to the bustling streets of Cairo, there\'s something for every traveler.</p><p>In this comprehensive guide, we\'ll explore the top destinations that should be on every visitor\'s itinerary.</p>',
            '<p>The Egyptian Pyramids are among the most iconic structures in the world. Built thousands of years ago, they continue to amaze visitors today.</p><p>Learn about the history, architecture, and best ways to experience these ancient wonders.</p>',
            '<p>Planning your trip to Egypt? Understanding the weather patterns and seasons can help you make the most of your visit.</p><p>We\'ll break down the best times to visit different regions of Egypt.</p>',
            '<p>A Nile cruise is one of the most romantic and relaxing ways to explore Egypt. Sail between Luxor and Aswan while visiting ancient temples.</p><p>Discover what to expect on your cruise, from accommodations to daily activities.</p>',
            '<p>Egyptian cuisine is rich, flavorful, and diverse. From street food to fine dining, there\'s something to satisfy every palate.</p><p>Join us on a culinary journey through Egypt\'s most delicious dishes.</p>',
        ];

        foreach ($blogTitles as $index => $title) {
            $slug = Str::slug($title);
            
            if (Blog::where('slug', $slug)->exists()) {
                continue;
            }

            $blog = Blog::create([
                'slug' => $slug,
                'featured_image' => 'https://images.unsplash.com/photo-1539650116574-75c0c6d73a6e?w=800',
                'gallery' => [
                    'https://images.unsplash.com/photo-1539650116574-75c0c6d73a6e?w=800',
                    'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=800',
                ],
                'active' => true,
                'status' => BlogStatus::PUBLISHED->value,
                'published_at' => Carbon::now()->subDays(rand(1, 90)),
                'published_by_id' => $admin?->id,
                'display_order' => $index + 1,
            ]);

            // Add translations
            foreach (config('translatable.locales') as $locale) {
                $blog->translateOrNew($locale)->title = $title;
                $blog->translateOrNew($locale)->description = $blogDescriptions[$index % count($blogDescriptions)] . 
                    '<p>This is a detailed blog post about ' . strtolower($title) . '. It contains valuable information for travelers planning their visit to Egypt.</p>';
                $blog->translateOrNew($locale)->tags = 'egypt, travel, tourism, ' . Str::slug($title, ', ');
            }
            $blog->save();

            // Attach random categories
            $randomCategories = $blogCategories->random(rand(1, 3));
            $blog->categories()->attach($randomCategories->pluck('id'));

            // Create comments for this blog
            $this->createBlogComments($blog, rand(3, 8));
        }
    }

    private function createBlogComments(Blog $blog, int $count): void
    {
        $names = ['John Doe', 'Jane Smith', 'Ahmed Ali', 'Sarah Johnson', 'Mohamed Hassan', 'Emily Brown', 'Omar Khaled', 'Lisa Anderson'];
        $emails = ['john@example.com', 'jane@example.com', 'ahmed@example.com', 'sarah@example.com', 'mohamed@example.com', 'emily@example.com', 'omar@example.com', 'lisa@example.com'];
        
        $comments = [
            'Great article! Very informative and helpful.',
            'Thanks for sharing this. Planning my trip now!',
            'I visited last year and this guide would have been perfect.',
            'Excellent tips! Especially the part about local customs.',
            'Beautiful photos! Makes me want to visit immediately.',
            'Very detailed and well-written. Thank you!',
            'This helped me plan my itinerary perfectly.',
            'Love the recommendations. Can\'t wait to try them!',
        ];

        $replies = [
            'I agree! This was very helpful.',
            'Thanks for the additional info!',
            'Great point!',
            'Exactly what I was thinking.',
        ];

        for ($i = 0; $i < $count; $i++) {
            $name = $names[array_rand($names)];
            $email = $emails[array_rand($emails)];
            $comment = $comments[array_rand($comments)];

            $blogComment = BlogComment::create([
                'blog_id' => $blog->id,
                'name' => $name,
                'email' => $email,
                'comment' => $comment,
                'is_approved' => rand(0, 10) > 2, // 80% approved
                'is_featured' => rand(0, 10) > 8, // 20% featured
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);

            // Create some replies (30% chance)
            if (rand(0, 10) > 7) {
                BlogComment::create([
                    'blog_id' => $blog->id,
                    'parent_id' => $blogComment->id,
                    'name' => $names[array_rand($names)],
                    'email' => $emails[array_rand($emails)],
                    'comment' => $replies[array_rand($replies)],
                    'is_approved' => true,
                    'is_featured' => false,
                    'created_at' => $blogComment->created_at->addHours(rand(1, 48)),
                ]);
            }
        }
    }

    private function createHotelsWithRooms(): void
    {
        $cities = City::all();
        if ($cities->isEmpty()) {
            $this->command->warn('No cities found. Please run CitySeeder first.');
            return;
        }

        $hotelNames = [
            'Grand Nile Hotel',
            'Pyramid View Resort',
            'Luxor Palace Hotel',
            'Red Sea Paradise',
            'Cairo Central Hotel',
            'Aswan Riverside Resort',
            'Desert Oasis Hotel',
            'Nile Luxury Suites',
        ];

        foreach ($hotelNames as $index => $hotelName) {
            $slug = Str::slug($hotelName);
            
            if (Hotel::where('slug', $slug)->exists()) {
                continue;
            }

            $city = $cities->random();
            $hotel = Hotel::create([
                'slug' => $slug,
                'featured_image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800',
                'banner' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200',
                'gallery' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800',
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800',
                ],
                'stars' => rand(3, 5),
                'enabled' => true,
                'phone_contact' => '+20' . rand(1000000000, 9999999999),
                'whatsapp_contact' => '+20' . rand(1000000000, 9999999999),
            ]);

            // Add translations
            foreach (config('translatable.locales') as $locale) {
                $hotel->translateOrNew($locale)->name = $hotelName;
                $hotel->translateOrNew($locale)->description = '<p>' . $hotelName . ' offers world-class accommodations in the heart of ' . $city->name . '. Experience luxury and comfort with stunning views and exceptional service.</p>';
            }
            $hotel->address = rand(100, 999) . ' Main Street, ' . $city->name;
            $hotel->save();

            // Create rooms for this hotel
            $this->createRoomsForHotel($hotel, rand(3, 6));
        }
    }

    private function createRoomsForHotel(Hotel $hotel, int $count): void
    {
        $roomTypes = ['Standard Room', 'Deluxe Room', 'Suite', 'Presidential Suite', 'Family Room'];
        $hotelName = $hotel->translate('en')->name ?? 'Hotel';
        
        for ($i = 0; $i < $count; $i++) {
            $roomType = $roomTypes[array_rand($roomTypes)];
            $slug = Str::slug($hotelName . ' ' . $roomType . ' ' . ($i + 1));
            
            $room = Room::create([
                'hotel_id' => $hotel->id,
                'slug' => $slug,
                'featured_image' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800',
                'bed_count' => rand(1, 3),
                'max_capacity' => rand(2, 6),
                'night_price' => rand(50, 500),
                'enabled' => true,
            ]);

            // Add translations
            foreach (config('translatable.locales') as $locale) {
                $room->translateOrNew($locale)->name = $roomType;
                $room->translateOrNew($locale)->description = '<p>Spacious ' . strtolower($roomType) . ' with modern amenities and comfortable furnishings. Perfect for a relaxing stay.</p>';
            }
            $room->save();
        }
    }

    private function createTourReviews(): void
    {
        $tours = Tour::all();
        if ($tours->isEmpty()) {
            $this->command->warn('No tours found. Please run TourSeeder first.');
            return;
        }

        $names = ['John Doe', 'Jane Smith', 'Ahmed Ali', 'Sarah Johnson', 'Mohamed Hassan', 'Emily Brown'];
        $reviews = [
            'Amazing experience! The tour guide was knowledgeable and friendly.',
            'Best tour I\'ve ever taken. Highly recommend!',
            'Great value for money. Everything was well organized.',
            'The sights were breathtaking. A must-do experience.',
            'Professional service from start to finish.',
            'Exceeded my expectations. Will definitely book again!',
        ];

        foreach ($tours->take(10) as $tour) {
            // Create 2-5 reviews per tour
            for ($i = 0; $i < rand(2, 5); $i++) {
                TourReview::create([
                    'tour_id' => $tour->id,
                    'reviewer_name' => $names[array_rand($names)],
                    'rate' => rand(4, 5),
                    'content' => $reviews[array_rand($reviews)],
                    'created_at' => Carbon::now()->subDays(rand(1, 60)),
                ]);
            }
        }
    }

    private function createClients(): void
    {
        $emails = [
            'client1@test.com',
            'client2@test.com',
            'client3@test.com',
            'client4@test.com',
            'client5@test.com',
        ];

        $names = ['Test Client 1', 'Test Client 2', 'Test Client 3', 'Test Client 4', 'Test Client 5'];

        foreach ($emails as $index => $email) {
            if (!Client::where('email', $email)->exists()) {
                Client::create([
                    'name' => $names[$index],
                    'email' => $email,
                    'phone' => '+20123456789' . $index,
                    'password' => bcrypt('password'),
                    'nationality' => 'Egyptian',
                    'birthdate' => Carbon::now()->subYears(rand(20, 50)),
                ]);
            }
        }
    }

    private function createFAQs(): void
    {
        $faqs = [
            [
                'question' => 'What is the best time to visit Egypt?',
                'answer' => 'The best time to visit Egypt is during the cooler months from October to April. The weather is more pleasant for sightseeing and outdoor activities.',
            ],
            [
                'question' => 'Do I need a visa to visit Egypt?',
                'answer' => 'Most visitors need a visa to enter Egypt. You can obtain a visa on arrival at the airport or apply in advance at an Egyptian embassy.',
            ],
            [
                'question' => 'Is it safe to travel to Egypt?',
                'answer' => 'Egypt is generally safe for tourists. However, it\'s always recommended to stay informed about current travel advisories and follow local guidelines.',
            ],
            [
                'question' => 'What currency is used in Egypt?',
                'answer' => 'The official currency is the Egyptian Pound (EGP). US Dollars and Euros are also widely accepted in tourist areas.',
            ],
            [
                'question' => 'What should I pack for my trip?',
                'answer' => 'Pack light, breathable clothing for hot weather, comfortable walking shoes, sunscreen, a hat, and modest clothing for visiting religious sites.',
            ],
            [
                'question' => 'Can I drink tap water in Egypt?',
                'answer' => 'It\'s recommended to drink bottled water during your stay in Egypt to avoid any potential stomach issues.',
            ],
            [
                'question' => 'What languages are spoken in Egypt?',
                'answer' => 'Arabic is the official language. English is widely spoken in tourist areas, hotels, and by tour guides.',
            ],
            [
                'question' => 'How do I book a tour?',
                'answer' => 'You can book tours directly through our website by selecting your preferred tour and completing the booking form. Our team will confirm your reservation.',
            ],
        ];

        foreach ($faqs as $index => $faq) {
            if (!Faq::whereTranslation('question', $faq['question'])->exists()) {
                $faqModel = Faq::create([
                    'active' => true,
                    'is_active' => true,
                    'order' => $index + 1,
                ]);

                foreach (config('translatable.locales') as $locale) {
                    $faqModel->translateOrNew($locale)->question = $faq['question'];
                    $faqModel->translateOrNew($locale)->answer = $faq['answer'];
                }
                $faqModel->save();
            }
        }
    }
}

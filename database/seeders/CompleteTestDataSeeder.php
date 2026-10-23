<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Complete Test Data Seeder
 * 
 * This seeder runs all necessary seeders to populate the database
 * with comprehensive test data for frontend testing.
 * 
 * Usage: php artisan db:seed --class=CompleteTestDataSeeder
 */
class CompleteTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('========================================');
        $this->command->info('Starting Complete Test Data Seeder');
        $this->command->info('========================================');
        
        // Step 1: Basic Setup (Roles, Permissions, Admin)
        $this->command->info('Step 1: Setting up roles, permissions, and admin user...');
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(PermissionsSeeder::class);
        
        // Step 2: Reference Data (Categories, Destinations, etc.)
        $this->command->info('Step 2: Creating reference data...');
        $this->call(CategorySeeder::class);
        $this->call(DestinationSeeder::class);
        $this->call(DurationSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(CitySeeder::class);
        
        // Step 3: Blog Data
        $this->command->info('Step 3: Creating blog categories...');
        $this->call(BlogCategoriesSeeder::class);
        
        // Step 4: Amenities (for hotels)
        $this->command->info('Step 4: Creating amenities...');
        $this->call(AmenitySeeder::class);
        
        // Step 5: Tours
        $this->command->info('Step 5: Creating tours...');
        $this->call(TourSeeder::class);
        
        // Step 6: Trips
        $this->command->info('Step 6: Creating trips...');
        $this->call(TripSeeder::class);
        
        // Step 7: Settings and Pages
        $this->command->info('Step 7: Creating settings and pages...');
        $this->call(SettingsSeeder::class);
        $this->call(PagesSeeder::class);
        
        // Step 8: Frontend Test Data (Blogs, Hotels, Reviews, etc.)
        $this->command->info('Step 8: Creating frontend test data (blogs, hotels, reviews, FAQs)...');
        $this->call(FrontendTestDataSeeder::class);
        
        // Step 9: Optional - Bulk Trips (if needed for extensive testing)
        if ($this->command->confirm('Do you want to generate bulk trips data? (This may take a while)', false)) {
            $this->command->info('Step 9: Generating bulk trips data...');
            $this->call(BulkDataSeeder::class);
        }
        
        $this->command->info('========================================');
        $this->command->info('Complete Test Data Seeder finished!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('  Admin: admin@admin.com / password');
        $this->command->info('  Clients: client1@test.com to client5@test.com / password');
        $this->command->info('');
        $this->command->info('You can now test the frontend with this data!');
    }
}

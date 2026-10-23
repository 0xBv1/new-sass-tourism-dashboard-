<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Roles and Permissions (must be first)
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(PermissionsSeeder::class);
        
        // Admin User
        $this->call(AdminSeeder::class);
        
        // Basic Data
        $this->call(CurrenciesSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(PagesSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(CitySeeder::class);
        
        // Categories and Destinations
        $this->call(CategorySeeder::class);
        $this->call(DestinationSeeder::class);
        
        // Blog
        $this->call(BlogCategoriesSeeder::class);
        
        // Tours and Accommodation
        $this->call(AmenitySeeder::class);
        $this->call(DurationSeeder::class);
        $this->call(AccommodationCategorySeeder::class);
        $this->call(GroupSizeSeeder::class);
    }
}

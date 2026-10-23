<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions_seeder_path = database_path('seeders/Permissions');
        if (File::exists($permissions_seeder_path)) {
            $dir_files = array_diff(scandir($permissions_seeder_path), ['..', '.']);
            foreach ($dir_files as $file) {
                if (!\Str::of($file)->endsWith('.php')) {
                    continue;
                }
                $class = str_replace('.php', '', $file);
                $this->call("Database\Seeders\Permissions\\" . $class);
            }
        }
    }
}

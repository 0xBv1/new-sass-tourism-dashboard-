<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SqlDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder imports data from a SQL file and replaces image URLs with APP_URL
     */
    public function run(): void
    {
        $sqlFile = base_path('yalu32le_tourism_api (1).sql');
        
        if (!File::exists($sqlFile)) {
            $this->command->warn('SQL file not found: ' . $sqlFile);
            $this->command->info('Skipping SQL data import...');
            return;
        }

        $this->command->info('Starting SQL data import...');
        $this->command->info('Reading SQL file...');

        // Read the SQL file
        $sqlContent = File::get($sqlFile);
        
        // Get APP_URL from environment
        $appUrl = rtrim(config('app.url'), '/');
        
        if (empty($appUrl)) {
            $appUrl = rtrim(env('APP_URL', 'http://localhost'), '/');
        }

        $this->command->info('Replacing image URLs with APP_URL: ' . $appUrl);

        // Find and replace common image URL patterns
        // Pattern 1: Replace full URLs with domain (most common pattern)
        // Match: https://tourism-api.perfectsolutions4u.com/storage/...
        // Also match URLs in INSERT statements with spaces in filenames
        $sqlContent = preg_replace_callback(
            '/https?:\/\/[^\/\s"\']+(\/(?:storage|assets)\/[^"\'\s,)]+)/i',
            function ($matches) use ($appUrl) {
                return $appUrl . $matches[1];
            },
            $sqlContent
        );
        
        // Pattern 1a: Handle URLs that might have spaces or special characters
        $sqlContent = preg_replace_callback(
            '/https?:\/\/[^\/\s"\']+(\/(?:storage|assets)\/[^"\']+)/i',
            function ($matches) use ($appUrl) {
                // Clean up the path
                $path = trim($matches[1]);
                return $appUrl . $path;
            },
            $sqlContent
        );

        // Pattern 1b: Full URLs like http://example.com/storage/image.jpg (fallback)
        $sqlContent = preg_replace_callback(
            '/(["\'])(https?:\/\/[^"\']+\.(jpg|jpeg|png|gif|webp|svg|bmp|ico))(["\'])/i',
            function ($matches) use ($appUrl) {
                $imagePath = $this->extractImagePath($matches[2]);
                return $matches[1] . $appUrl . '/' . ltrim($imagePath, '/') . $matches[4];
            },
            $sqlContent
        );

        // Pattern 2: URLs in JSON fields
        $sqlContent = preg_replace_callback(
            '/(["\']https?:\/\/[^"\']+\.(jpg|jpeg|png|gif|webp|svg|bmp|ico)[^"\']*["\'])/i',
            function ($matches) use ($appUrl) {
                $url = trim($matches[1], '"\'');
                $imagePath = $this->extractImagePath($url);
                return '"' . $appUrl . '/' . ltrim($imagePath, '/') . '"';
            },
            $sqlContent
        );

        // Pattern 3: Storage paths that might have full URLs
        $sqlContent = preg_replace_callback(
            '/(storage\/[^"\'\s,)]+\.(jpg|jpeg|png|gif|webp|svg|bmp|ico))/i',
            function ($matches) use ($appUrl) {
                return $appUrl . '/' . $matches[1];
            },
            $sqlContent
        );

        // Pattern 4: assets paths
        $sqlContent = preg_replace_callback(
            '/(assets\/[^"\'\s,)]+\.(jpg|jpeg|png|gif|webp|svg|bmp|ico))/i',
            function ($matches) use ($appUrl) {
                return $appUrl . '/' . $matches[1];
            },
            $sqlContent
        );

        $this->command->info('Executing SQL statements...');

        // Split SQL into individual statements
        $statements = $this->splitSqlStatements($sqlContent);

        $totalStatements = count($statements);
        $this->command->info("Found {$totalStatements} SQL statements to execute");

        $progressBar = $this->command->getOutput()->createProgressBar($totalStatements);
        $progressBar->start();

        $executed = 0;
        $errors = 0;

        DB::beginTransaction();

        try {
            foreach ($statements as $statement) {
                $statement = trim($statement);
                
                // Skip empty statements and comments
                if (empty($statement) || 
                    strpos($statement, '--') === 0 || 
                    strpos($statement, '/*') === 0 ||
                    strpos($statement, 'SET ') === 0 ||
                    strpos($statement, 'START TRANSACTION') === 0 ||
                    strpos($statement, 'COMMIT') === 0 ||
                    strpos($statement, 'CREATE TABLE') === 0) {
                    $progressBar->advance();
                    continue;
                }

                try {
                    // Execute INSERT, UPDATE, or other data statements
                    if (stripos($statement, 'INSERT') === 0 || 
                        stripos($statement, 'UPDATE') === 0 ||
                        stripos($statement, 'DELETE') === 0) {
                        DB::statement($statement);
                        $executed++;
                    }
                } catch (\Exception $e) {
                    $errors++;
                    // Log error but continue
                    if ($this->command->getOutput()->isVerbose()) {
                        $this->command->warn('Error executing statement: ' . substr($statement, 0, 100) . '...');
                        $this->command->warn('Error: ' . $e->getMessage());
                    }
                }

                $progressBar->advance();
            }

            DB::commit();
            $progressBar->finish();
            $this->command->newLine(2);

            $this->command->info("SQL import completed!");
            $this->command->info("Executed: {$executed} statements");
            if ($errors > 0) {
                $this->command->warn("Errors: {$errors} statements failed");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $progressBar->finish();
            $this->command->newLine(2);
            $this->command->error('SQL import failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extract image path from full URL
     */
    private function extractImagePath(string $url): string
    {
        // Remove protocol and domain
        $path = parse_url($url, PHP_URL_PATH);
        
        // If path starts with /storage, keep it
        if (strpos($path, '/storage') === 0) {
            return $path;
        }
        
        // If path starts with /assets, keep it
        if (strpos($path, '/assets') === 0) {
            return $path;
        }
        
        // If path starts with /public, remove /public
        if (strpos($path, '/public') === 0) {
            return substr($path, 7);
        }
        
        // Otherwise, assume it's a storage path
        if (strpos($path, 'storage') !== false) {
            return '/storage/' . basename($path);
        }
        
        return '/storage/' . ltrim($path, '/');
    }

    /**
     * Split SQL content into individual statements
     */
    private function splitSqlStatements(string $sql): array
    {
        // Remove SQL comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // Split by semicolon, but preserve strings
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        
        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];
            $prevChar = $i > 0 ? $sql[$i - 1] : '';
            
            // Handle string escaping
            if ($char === '\\' && $inString) {
                $current .= $char;
                if ($i + 1 < strlen($sql)) {
                    $current .= $sql[++$i];
                }
                continue;
            }
            
            // Toggle string state
            if (($char === '"' || $char === "'") && !$inString) {
                $inString = true;
                $stringChar = $char;
            } elseif ($char === $stringChar && $inString) {
                $inString = false;
                $stringChar = '';
            }
            
            $current .= $char;
            
            // If we hit a semicolon and we're not in a string, it's the end of a statement
            if ($char === ';' && !$inString) {
                $statements[] = trim($current);
                $current = '';
            }
        }
        
        // Add remaining content if any
        if (!empty(trim($current))) {
            $statements[] = trim($current);
        }
        
        return array_filter($statements, function($stmt) {
            return !empty(trim($stmt));
        });
    }
}

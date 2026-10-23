<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class InstallService
{
    /**
     * Check if application is already installed
     */
    public function isInstalled(): bool
    {
        return file_exists($this->installedFlagPath());
    }

    /**
     * Ensure APP_KEY exists
     */
    public function ensureAppKey(): void
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $envPath);
            } else {
                file_put_contents($envPath, "APP_NAME=Laravel\nAPP_ENV=local\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=http://localhost\n");
            }
        }

        $envContent = file_get_contents($envPath);
        
        // Check if APP_KEY is empty or missing
        if (empty(env('APP_KEY')) || !preg_match('/^APP_KEY=base64:.+/m', $envContent)) {
            try {
                Artisan::call('key:generate', ['--force' => true]);
            } catch (\Exception $e) {
                // If key generation fails, create a temporary key
                $key = 'base64:' . base64_encode(random_bytes(32));
                $envContent = preg_replace('/^APP_KEY=.*/m', 'APP_KEY=' . $key, $envContent);
                if (!preg_match('/^APP_KEY=/m', $envContent)) {
                    $envContent = "APP_KEY={$key}\n" . $envContent;
                }
                file_put_contents($envPath, $envContent);
            }
        }
    }

    /**
     * Mark application as installed
     */
    public function markAsInstalled(): void
    {
        file_put_contents($this->installedFlagPath(), now()->toDateTimeString());
    }

    /**
     * Keep the flag outside tenant storage. storage_path() changes per company.
     */
    public function installedFlagPath(): string
    {
        return base_path('storage/app/installed');
    }

    /**
     * Check database connection
     */
    public function checkDatabaseConnection(array $config): array
    {
        try {
            config([
                'database.connections.mysql.host' => $config['host'],
                'database.connections.mysql.port' => $config['port'],
                'database.connections.mysql.database' => $config['database'],
                'database.connections.mysql.username' => $config['username'],
                'database.connections.mysql.password' => $config['password'],
            ]);

            DB::connection('mysql')->getPdo();
            
            return ['success' => true, 'message' => 'تم الاتصال بقاعدة البيانات بنجاح'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'فشل الاتصال بقاعدة البيانات: ' . $e->getMessage()];
        }
    }

    /**
     * Run migrations and installation commands
     */
    public function runMigrations(): array
    {
        try {
            // Ensure storage link exists
            if (!file_exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }

            // Run app:install command which includes:
            // - key:generate
            // - migrate:fresh
            // - db:seed
            // - countries:fetch (optional)
            // - passport:install (optional)
            // - optimize:clear
            $exitCode = Artisan::call('app:install');
            
            // Check if command executed successfully
            if ($exitCode === 0) {
                return ['success' => true, 'message' => 'تم تنفيذ التثبيت بنجاح (Migrations, Seeders, Passport, Countries)'];
            } else {
                $output = Artisan::output();
                Log::warning('App install command returned non-zero exit code', [
                    'exit_code' => $exitCode,
                    'output' => $output,
                ]);
                return ['success' => false, 'message' => 'فشل تنفيذ التثبيت: Exit code ' . $exitCode];
            }
        } catch (\Exception $e) {
            // Log the full exception for debugging
            Log::error('Migration error in InstallService', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return ['success' => false, 'message' => 'فشل تنفيذ التثبيت: ' . $e->getMessage()];
        }
    }

    /**
     * Create admin user
     */
    public function createAdminUser(array $data): array
    {
        try {
            // Check if roles table exists
            if (!Schema::hasTable('roles')) {
                Log::error('Roles table does not exist - migrations may have failed');
                return ['success' => false, 'message' => 'فشل إنشاء حساب الأدمن: جدول الأدوار غير موجود. يرجى التحقق من نجاح migrations'];
            }

            // Create roles first
            $this->createRoles();

            // Create admin user
            $admin = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]
            );

            // Assign Administrator role
            $adminRole = Role::where('name', 'Administrator')->first();
            if ($adminRole) {
                $admin->assignRole($adminRole);
            } else {
                Log::warning('Administrator role not found after creation attempt');
            }

            return ['success' => true, 'message' => 'تم إنشاء حساب الأدمن بنجاح', 'user' => $admin];
        } catch (\Exception $e) {
            Log::error('Failed to create admin user', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return ['success' => false, 'message' => 'فشل إنشاء حساب الأدمن: ' . $e->getMessage()];
        }
    }

    /**
     * Create basic roles
     */
    private function createRoles(): void
    {
        try {
            // Check if roles table exists
            if (!Schema::hasTable('roles')) {
                Log::error('Cannot create roles - roles table does not exist');
                return;
            }

            $roles = ['Administrator', 'Editor', 'Operator'];
            
            foreach ($roles as $roleName) {
                try {
                    Role::updateOrCreate(
                        ['name' => $roleName],
                        ['guard_name' => 'web']
                    );
                } catch (\Exception $e) {
                    Log::warning("Failed to create role: {$roleName}", [
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to create roles', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    /**
     * Run seeders
     */
    public function runSeeders(): array
    {
        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\Seeders\RolesAndPermissionsSeeder',
                '--force' => true
            ]);
            
            Artisan::call('db:seed', [
                '--class' => 'Database\Seeders\PermissionsSeeder',
                '--force' => true
            ]);

            return ['success' => true, 'message' => 'تم تنفيذ Seeders بنجاح'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'فشل تنفيذ Seeders: ' . $e->getMessage()];
        }
    }

    /**
     * Check if .env file exists
     */
    public function checkEnvFile(): bool
    {
        return file_exists(base_path('.env'));
    }

    /**
     * Update .env file with database configuration
     */
    public function updateEnvFile(array $config): array
    {
        try {
            $envPath = base_path('.env');
            
            if (!file_exists($envPath)) {
                if (file_exists(base_path('.env.example'))) {
                    copy(base_path('.env.example'), $envPath);
                } else {
                    // Create basic .env file if .env.example doesn't exist
                    file_put_contents($envPath, "APP_NAME=Laravel\nAPP_ENV=local\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=http://localhost\n\nDB_CONNECTION=mysql\nDB_HOST=127.0.0.1\nDB_PORT=3306\nDB_DATABASE=\nDB_USERNAME=\nDB_PASSWORD=\n");
                }
            }

            $envContent = file_get_contents($envPath);
            
            // Update database configuration
            $envContent = preg_replace('/DB_HOST=(.*)/', 'DB_HOST=' . $config['host'], $envContent);
            $envContent = preg_replace('/DB_PORT=(.*)/', 'DB_PORT=' . $config['port'], $envContent);
            $envContent = preg_replace('/DB_DATABASE=(.*)/', 'DB_DATABASE=' . $config['database'], $envContent);
            $envContent = preg_replace('/DB_USERNAME=(.*)/', 'DB_USERNAME=' . $config['username'], $envContent);
            $password = $config['password'] ?? '';
            $envContent = preg_replace('/DB_PASSWORD=(.*)/', 'DB_PASSWORD=' . $password, $envContent);

            // Set APP_KEY if not set
            if (strpos($envContent, 'APP_KEY=') === false || (strpos($envContent, 'APP_KEY=') !== false && empty(env('APP_KEY')))) {
                Artisan::call('key:generate', ['--force' => true]);
            }

            file_put_contents($envPath, $envContent);

            return ['success' => true, 'message' => 'تم تحديث ملف .env بنجاح'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'فشل تحديث ملف .env: ' . $e->getMessage()];
        }
    }

    /**
     * Update .env file with frontend URL
     */
    public function updateEnvFileWithFrontendUrl(string $frontendUrl): array
    {
        try {
            $envPath = base_path('.env');
            
            if (!file_exists($envPath)) {
                return ['success' => false, 'message' => 'ملف .env غير موجود'];
            }

            $envContent = file_get_contents($envPath);
            
            // Remove trailing slash if present
            $frontendUrl = rtrim($frontendUrl, '/');
            
            // Update or add APP_FRONT_URL
            if (preg_match('/^APP_FRONT_URL=(.*)/m', $envContent)) {
                $envContent = preg_replace('/^APP_FRONT_URL=(.*)/m', 'APP_FRONT_URL=' . $frontendUrl, $envContent);
            } else {
                // Add APP_FRONT_URL after APP_URL
                if (preg_match('/^APP_URL=(.*)/m', $envContent)) {
                    $envContent = preg_replace('/^APP_URL=(.*)/m', "APP_URL=$1\nAPP_FRONT_URL=" . $frontendUrl, $envContent);
                } else {
                    // If APP_URL doesn't exist, add both
                    $envContent = "APP_FRONT_URL=" . $frontendUrl . "\n" . $envContent;
                }
            }

            file_put_contents($envPath, $envContent);

            return ['success' => true, 'message' => 'تم تحديث رابط الواجهة الأمامية بنجاح'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'فشل تحديث رابط الواجهة الأمامية: ' . $e->getMessage()];
        }
    }

    /**
     * Update .env file with payment credentials
     */
    public function updateEnvFileWithPaymentCredentials(array $credentials): array
    {
        try {
            $envPath = base_path('.env');
            
            if (!file_exists($envPath)) {
                return ['success' => false, 'message' => 'ملف .env غير موجود'];
            }

            $envContent = file_get_contents($envPath);
            
            // PayPal Configuration
            if (isset($credentials['paypal_mode'])) {
                $envContent = $this->updateOrAddEnvVariable($envContent, 'PAYPAL_MODE', $credentials['paypal_mode']);
            }
            
            if (isset($credentials['paypal_client_id']) && !empty($credentials['paypal_client_id'])) {
                // Note: PayPal credentials are stored in config file, not .env
                // We'll need to update the config file or store in .env for later use
                $envContent = $this->updateOrAddEnvVariable($envContent, 'PAYPAL_CLIENT_ID', $credentials['paypal_client_id']);
            }
            
            if (isset($credentials['paypal_client_secret']) && !empty($credentials['paypal_client_secret'])) {
                $envContent = $this->updateOrAddEnvVariable($envContent, 'PAYPAL_CLIENT_SECRET', $credentials['paypal_client_secret']);
            }
            
            if (isset($credentials['paypal_app_id']) && !empty($credentials['paypal_app_id'])) {
                $envContent = $this->updateOrAddEnvVariable($envContent, 'PAYPAL_APP_ID', $credentials['paypal_app_id']);
            }
            
            // Fawaterk Configuration
            if (isset($credentials['fawaterak_base_url']) && !empty($credentials['fawaterak_base_url'])) {
                $envContent = $this->updateOrAddEnvVariable($envContent, 'FAWATERAK_BASE_URL', $credentials['fawaterak_base_url']);
            }
            
            if (isset($credentials['fawaterak_api_key']) && !empty($credentials['fawaterak_api_key'])) {
                $envContent = $this->updateOrAddEnvVariable($envContent, 'FAWATERAK_API_KEY', $credentials['fawaterak_api_key']);
            }

            file_put_contents($envPath, $envContent);

            return ['success' => true, 'message' => 'تم حفظ بيانات الدفع بنجاح'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'فشل حفظ بيانات الدفع: ' . $e->getMessage()];
        }
    }

    /**
     * Helper method to update or add environment variable
     */
    private function updateOrAddEnvVariable(string $envContent, string $key, string $value): string
    {
        // Add quotes for values that contain spaces or special characters
        $quotedValue = preg_match('/[\s"\'#]/', $value) ? '"' . addslashes($value) . '"' : $value;
        
        if (preg_match('/^' . preg_quote($key, '/') . '=(.*)/m', $envContent)) {
            $envContent = preg_replace('/^' . preg_quote($key, '/') . '=(.*)/m', $key . '=' . $quotedValue, $envContent);
        } else {
            // Add after APP_FRONT_URL or at the end
            if (preg_match('/^APP_FRONT_URL=(.*)/m', $envContent)) {
                $envContent = preg_replace('/^APP_FRONT_URL=(.*)/m', "APP_FRONT_URL=$1\n{$key}={$quotedValue}", $envContent);
            } else {
                $envContent .= "\n{$key}={$quotedValue}";
            }
        }
        return $envContent;
    }

    /**
     * Update .env file with email configuration
     */
    public function updateEnvFileWithEmailConfig(array $config): array
    {
        try {
            $envPath = base_path('.env');
            
            if (!file_exists($envPath)) {
                return ['success' => false, 'message' => 'ملف .env غير موجود'];
            }

            $envContent = file_get_contents($envPath);
            
            // Mail Configuration
            $mailMailer = $config['mail_mailer'] ?? 'smtp';
            $mailHost = $config['mail_host'] ?? 'mailhog';
            $mailPort = $config['mail_port'] ?? '1025';
            $mailUsername = $config['mail_username'] ?? 'null';
            $mailPassword = $config['mail_password'] ?? 'null';
            $mailEncryption = $config['mail_encryption'] ?? 'null';
            $mailFromAddress = $config['mail_from_address'] ?? 'hello@example.com';
            $mailFromName = $config['mail_from_name'] ?? '${APP_NAME}';
            
            // Handle null values
            if ($mailUsername === 'null' || empty($mailUsername)) {
                $mailUsername = 'null';
            }
            if ($mailPassword === 'null' || empty($mailPassword)) {
                $mailPassword = 'null';
            }
            if ($mailEncryption === 'null' || empty($mailEncryption)) {
                $mailEncryption = 'null';
            }
            
            // Update or add email configuration
            $envContent = $this->updateOrAddEnvVariable($envContent, 'MAIL_MAILER', $mailMailer);
            $envContent = $this->updateOrAddEnvVariable($envContent, 'MAIL_HOST', $mailHost);
            $envContent = $this->updateOrAddEnvVariable($envContent, 'MAIL_PORT', $mailPort);
            $envContent = $this->updateOrAddEnvVariable($envContent, 'MAIL_USERNAME', $mailUsername);
            $envContent = $this->updateOrAddEnvVariable($envContent, 'MAIL_PASSWORD', $mailPassword);
            $envContent = $this->updateOrAddEnvVariable($envContent, 'MAIL_ENCRYPTION', $mailEncryption);
            $envContent = $this->updateOrAddEnvVariable($envContent, 'MAIL_FROM_ADDRESS', $mailFromAddress);
            $envContent = $this->updateOrAddEnvVariable($envContent, 'MAIL_FROM_NAME', $mailFromName);

            file_put_contents($envPath, $envContent);

            return ['success' => true, 'message' => 'تم حفظ إعدادات البريد الإلكتروني بنجاح'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'فشل حفظ إعدادات البريد الإلكتروني: ' . $e->getMessage()];
        }
    }

    /**
     * Clear application cache
     */
    public function clearCache(): void
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
    }
}

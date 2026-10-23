<?php

namespace App\Http\Controllers;

use App\Services\InstallService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InstallController extends Controller
{
    protected InstallService $installService;

    public function __construct(InstallService $installService)
    {
        $this->installService = $installService;
    }

    /**
     * Show installation welcome page
     */
    public function index()
    {
        // Ensure APP_KEY exists before proceeding
        try {
            $this->installService->ensureAppKey();
        } catch (\Exception $e) {
            // Continue anyway - we'll handle it later
        }

        // Check if installed without accessing database
        try {
            if ($this->installService->isInstalled()) {
                return redirect()->route('login');
            }
        } catch (\Exception $e) {
            // Continue with installation if check fails
        }

        return view('install.welcome');
    }

    /**
     * Show requirements check page
     */
    public function requirements()
    {
        // Ensure APP_KEY exists
        try {
            $this->installService->ensureAppKey();
        } catch (\Exception $e) {
            // Continue anyway
        }

        if ($this->installService->isInstalled()) {
            return redirect()->route('login');
        }

        $requirements = $this->checkRequirements();
        
        return view('install.requirements', compact('requirements'));
    }

    /**
     * Show database configuration page
     */
    public function database()
    {
        try {
            // Ensure APP_KEY exists
            try {
                $this->installService->ensureAppKey();
            } catch (\Exception $e) {
                // Log but continue
                Log::warning('Failed to ensure APP_KEY in database method: ' . $e->getMessage());
            }

            // Check if installed (with error handling)
            try {
                if ($this->installService->isInstalled()) {
                    return redirect()->route('login');
                }
            } catch (\Exception $e) {
                // If check fails, continue with installation
                Log::info('Installation check failed, continuing with installation: ' . $e->getMessage());
            }

            return view('install.database');
        } catch (\Exception $e) {
            // Log the full error
            Log::error('Error in database method: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            // Return a simple error view instead of crashing
            return response()->view('install.error', [
                'message' => 'An error occurred while loading the database configuration page. Please check the logs.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Test database connection
     */
    public function testDatabase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required|string',
            'port' => 'required|string',
            'database' => 'required|string',
            'username' => 'required|string',
            'password' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->installService->checkDatabaseConnection($request->all());

        return response()->json($result);
    }

    /**
     * Process database configuration
     */
    public function processDatabase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required|string',
            'port' => 'required|string',
            'database' => 'required|string',
            'username' => 'required|string',
            'password' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update .env file
        $envResult = $this->installService->updateEnvFile($request->all());
        
        if (!$envResult['success']) {
            return back()->with('error', $envResult['message'])->withInput();
        }

        // Clear cache
        $this->installService->clearCache();

        // Test connection
        $connectionResult = $this->installService->checkDatabaseConnection($request->all());
        
        if (!$connectionResult['success']) {
            return back()->with('error', $connectionResult['message'])->withInput();
        }

        // Store database config in session
        session(['database_config' => $request->all()]);

        return redirect()->route('install.admin');
    }

    /**
     * Show payment setup page
     */
    public function payment()
    {
        Log::info('Payment page accessed', [
            'session_id' => session()->getId(),
            'has_database_config' => session()->has('database_config'),
            'has_admin_config' => session()->has('admin_config'),
            'is_installed' => $this->installService->isInstalled(),
        ]);

        // Ensure APP_KEY exists
        try {
            $this->installService->ensureAppKey();
        } catch (\Exception $e) {
            Log::warning('Failed to ensure APP_KEY in payment method', ['error' => $e->getMessage()]);
        }

        if ($this->installService->isInstalled()) {
            Log::info('Redirecting to login - already installed');
            return redirect()->route('login');
        }

        if (!session()->has('database_config')) {
            Log::warning('Redirecting to database - missing database_config in session', [
                'session_keys' => array_keys(session()->all()),
            ]);
            return redirect()->route('install.database');
        }

        if (!session()->has('admin_config')) {
            Log::warning('Redirecting to admin - missing admin_config in session', [
                'session_keys' => array_keys(session()->all()),
            ]);
            return redirect()->route('install.admin');
        }

        Log::info('Payment page rendered successfully');
        return view('install.payment');
    }

    /**
     * Process payment setup
     */
    public function processPayment(Request $request)
    {
        Log::info('Processing payment setup', [
            'session_id' => session()->getId(),
            'has_database_config' => session()->has('database_config'),
            'has_admin_config' => session()->has('admin_config'),
            'has_skip' => $request->has('skip'),
            'has_setup_payment' => $request->has('setup_payment'),
            'setup_payment_value' => $request->input('setup_payment'),
        ]);

        // Check if database and admin configs exist in session
        if (!session()->has('database_config')) {
            Log::warning('Redirecting to database from processPayment - missing database_config', [
                'session_keys' => array_keys(session()->all()),
            ]);
            return redirect()->route('install.database')->with('error', 'Please setup database first');
        }

        if (!session()->has('admin_config')) {
            Log::warning('Redirecting to admin from processPayment - missing admin_config', [
                'session_keys' => array_keys(session()->all()),
            ]);
            return redirect()->route('install.admin')->with('error', 'Please create admin account first');
        }

        // If skip button was clicked, go directly to email setup
        if ($request->has('skip')) {
            Log::info('Skip button clicked - redirecting to email setup');
            return redirect()->route('install.email');
        }

        // If setup_payment is not checked, go to process
        if (!$request->has('setup_payment') || !$request->input('setup_payment')) {
            Log::info('Setup payment not checked - redirecting to process');
            return redirect()->route('install.process');
        }

        // If setup_payment is checked, validate that at least one payment method has credentials
        if ($request->has('setup_payment') && $request->input('setup_payment')) {
            $hasPaypal = !empty($request->input('paypal_client_id')) && !empty($request->input('paypal_client_secret'));
            $hasFawaterk = !empty($request->input('fawaterak_api_key'));
            
            Log::info('Payment credentials check', [
                'has_paypal' => $hasPaypal,
                'has_fawaterk' => $hasFawaterk,
            ]);
            
            if (!$hasPaypal && !$hasFawaterk) {
                Log::warning('No payment credentials provided');
                return back()->with('error', 'Please enter at least PayPal or Fawaterk credentials')->withInput();
            }
        }

        // Validate payment credentials if setup_payment is checked
        $validator = Validator::make($request->all(), [
            'paypal_mode' => 'nullable|in:sandbox,live',
            'paypal_client_id' => 'nullable|string|max:255',
            'paypal_client_secret' => 'nullable|string|max:255',
            'paypal_app_id' => 'nullable|string|max:255',
            'fawaterak_base_url' => 'nullable|url|max:255',
            'fawaterak_api_key' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::warning('Payment validation failed', ['errors' => $validator->errors()->toArray()]);
            return back()->withErrors($validator)->withInput();
        }

        // Save payment credentials to .env
        $envResult = $this->installService->updateEnvFileWithPaymentCredentials($request->all());
        if (!$envResult['success']) {
            Log::error('Failed to save payment credentials', ['message' => $envResult['message']]);
            return back()->with('error', $envResult['message'])->withInput();
        }

        // Store payment config in session
        session(['payment_config' => $request->all()]);
        Log::info('Payment credentials saved successfully - redirecting to email setup');

        return redirect()->route('install.email');
    }

    /**
     * Show email configuration page
     */
    public function email()
    {
        Log::info('Email page accessed', [
            'session_id' => session()->getId(),
            'has_database_config' => session()->has('database_config'),
            'has_admin_config' => session()->has('admin_config'),
            'has_payment_config' => session()->has('payment_config'),
            'is_installed' => $this->installService->isInstalled(),
        ]);

        // Ensure APP_KEY exists
        try {
            $this->installService->ensureAppKey();
        } catch (\Exception $e) {
            Log::warning('Failed to ensure APP_KEY in email method', ['error' => $e->getMessage()]);
        }

        if ($this->installService->isInstalled()) {
            Log::info('Redirecting to login - already installed');
            return redirect()->route('login');
        }

        if (!session()->has('database_config')) {
            Log::warning('Redirecting to database - missing database_config in session', [
                'session_keys' => array_keys(session()->all()),
            ]);
            return redirect()->route('install.database');
        }

        if (!session()->has('admin_config')) {
            Log::warning('Redirecting to admin - missing admin_config in session', [
                'session_keys' => array_keys(session()->all()),
            ]);
            return redirect()->route('install.admin');
        }

        Log::info('Email page rendered successfully');
        return view('install.email');
    }

    /**
     * Process email configuration
     */
    public function processEmail(Request $request)
    {
        Log::info('Processing email setup', [
            'session_id' => session()->getId(),
            'has_database_config' => session()->has('database_config'),
            'has_admin_config' => session()->has('admin_config'),
            'has_payment_config' => session()->has('payment_config'),
            'has_email_config' => session()->has('email_config'),
            'has_skip' => $request->has('skip'),
        ]);

        // Check if database and admin configs exist in session
        if (!session()->has('database_config')) {
            Log::warning('Redirecting to database from processEmail - missing database_config', [
                'session_keys' => array_keys(session()->all()),
            ]);
            return redirect()->route('install.database')->with('error', 'Please setup database first');
        }

        if (!session()->has('admin_config')) {
            Log::warning('Redirecting to admin from processEmail - missing admin_config', [
                'session_keys' => array_keys(session()->all()),
            ]);
            return redirect()->route('install.admin')->with('error', 'Please create admin account first');
        }

        // Check if email config already exists in session (prevent double submission)
        if (session()->has('email_config') && !$request->has('skip')) {
            Log::info('Email config already exists - redirecting to process to prevent double submission');
            return redirect()->route('install.process');
        }

        // If skip button was clicked, go directly to process
        if ($request->has('skip')) {
            Log::info('Skip button clicked - redirecting to process');
            // Mark as skipped in session
            session(['email_config' => ['skipped' => true]]);
            return redirect()->route('install.process');
        }

        // Validate email configuration
        $validator = Validator::make($request->all(), [
            'mail_mailer' => 'required|in:smtp,sendmail,mailgun,ses',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:null,tls,ssl',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::warning('Email validation failed', ['errors' => $validator->errors()->toArray()]);
            // Regenerate CSRF token on validation failure
            $request->session()->regenerateToken();
            return back()->withErrors($validator)->withInput();
        }

        // Save email configuration to .env
        $envResult = $this->installService->updateEnvFileWithEmailConfig($request->all());
        if (!$envResult['success']) {
            Log::error('Failed to save email configuration', ['message' => $envResult['message']]);
            // Regenerate CSRF token on error
            $request->session()->regenerateToken();
            return back()->with('error', $envResult['message'])->withInput();
        }

        // Store email config in session
        session(['email_config' => $request->all()]);
        Log::info('Email configuration saved successfully - redirecting to process');

        return redirect()->route('install.process');
    }

    /**
     * Show admin account creation page
     */
    public function admin()
    {
        // Ensure APP_KEY exists
        try {
            $this->installService->ensureAppKey();
        } catch (\Exception $e) {
            // Continue anyway
        }

        if ($this->installService->isInstalled()) {
            return redirect()->route('login');
        }

        if (!session()->has('database_config')) {
            return redirect()->route('install.database');
        }

        return view('install.admin');
    }

    /**
     * Process admin account creation and redirect to payment
     */
    public function processAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'frontend_url' => 'required|url|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Store admin data in session
        session(['admin_config' => $request->only(['name', 'email', 'password', 'frontend_url'])]);

        return redirect()->route('install.payment');
    }

    /**
     * Process installation
     */
    public function process()
    {
        Log::info('Process installation started', [
            'session_id' => session()->getId(),
            'has_database_config' => session()->has('database_config'),
            'has_admin_config' => session()->has('admin_config'),
            'has_payment_config' => session()->has('payment_config'),
            'session_keys' => array_keys(session()->all()),
        ]);

        // Get admin config from session (set in processAdmin)
        $adminConfig = session('admin_config');
        
        if (!$adminConfig) {
            Log::warning('Redirecting to admin from process - missing admin_config', [
                'session_keys' => array_keys(session()->all()),
            ]);
            return redirect()->route('install.admin')->with('error', 'Please create admin account first');
        }

        $databaseConfig = session('database_config');
        
        if (!$databaseConfig) {
            Log::warning('Redirecting to database from process - missing database_config', [
                'session_keys' => array_keys(session()->all()),
            ]);
            return redirect()->route('install.database')->with('error', 'Please setup database first');
        }

        Log::info('Starting installation process', [
            'admin_email' => $adminConfig['email'] ?? 'N/A',
            'database' => $databaseConfig['database'] ?? 'N/A',
        ]);

        try {
            // Update .env file with frontend URL
            Log::info('Updating frontend URL in .env');
            $envResult = $this->installService->updateEnvFileWithFrontendUrl($adminConfig['frontend_url']);
            if (!$envResult['success']) {
                Log::error('Failed to update frontend URL', ['message' => $envResult['message']]);
                return redirect()->route('install.admin')->with('error', $envResult['message'])->withInput();
            }

            // Payment credentials are already saved in processPayment method
            Log::info('Payment credentials check', [
                'has_payment_config' => session()->has('payment_config'),
            ]);

            // Run migrations with seeders (--seed flag runs DatabaseSeeder automatically)
            Log::info('Running migrations and seeders');
            
            // Store session data before migration in case it fails
            $sessionBackup = [
                'database_config' => session('database_config'),
                'admin_config' => session('admin_config'),
                'payment_config' => session('payment_config'),
            ];
            
            $migrationResult = $this->installService->runMigrations();
            if (!$migrationResult['success']) {
                Log::error('Migration failed', [
                    'message' => $migrationResult['message'],
                    'session_id' => session()->getId(),
                    'has_database_config' => session()->has('database_config'),
                    'has_admin_config' => session()->has('admin_config'),
                    'has_payment_config' => session()->has('payment_config'),
                    'session_keys' => array_keys(session()->all()),
                ]);
                
                // Restore session data if it was lost
                $sessionRestored = false;
                if (!session()->has('database_config') && isset($sessionBackup['database_config'])) {
                    session(['database_config' => $sessionBackup['database_config']]);
                    $sessionRestored = true;
                }
                if (!session()->has('admin_config') && isset($sessionBackup['admin_config'])) {
                    session(['admin_config' => $sessionBackup['admin_config']]);
                    $sessionRestored = true;
                }
                if (!session()->has('payment_config') && isset($sessionBackup['payment_config'])) {
                    session(['payment_config' => $sessionBackup['payment_config']]);
                    $sessionRestored = true;
                }
                
                if ($sessionRestored) {
                    Log::info('Session data restored from backup');
                }
                
                // Keep all session data for next request
                session()->keep(['database_config', 'admin_config', 'payment_config']);
                
                // Save session before redirect to ensure data is preserved
                session()->save();
                
                Log::info('Redirecting to payment page after migration failure', [
                    'session_id' => session()->getId(),
                    'has_database_config' => session()->has('database_config'),
                    'has_admin_config' => session()->has('admin_config'),
                    'has_payment_config' => session()->has('payment_config'),
                ]);
                
                // Redirect to payment page instead of back() to preserve session
                return redirect()->route('install.payment')
                    ->with('error', $migrationResult['message']);
            }
            Log::info('Migrations completed successfully');

            // Verify that roles table exists after migrations
            Log::info('Verifying database tables after migration');
            $rolesTableExists = \Illuminate\Support\Facades\Schema::hasTable('roles');
            Log::info('Roles table exists', ['exists' => $rolesTableExists]);
            
            if (!$rolesTableExists) {
                Log::error('Roles table does not exist after migrations - seeders may have failed');
                // Try to run seeders manually
                try {
                    Log::info('Attempting to run seeders manually');
                    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                    Log::info('Seeders completed');
                } catch (\Exception $e) {
                    Log::error('Failed to run seeders', ['message' => $e->getMessage()]);
                }
            }

            // Create admin user
            Log::info('Creating admin user');
            $adminResult = $this->installService->createAdminUser([
                'name' => $adminConfig['name'],
                'email' => $adminConfig['email'],
                'password' => $adminConfig['password'],
            ]);
            if (!$adminResult['success']) {
                Log::error('Failed to create admin user', ['message' => $adminResult['message']]);
                // Save session before redirect
                session()->save();
                return redirect()->route('install.payment')
                    ->with('error', $adminResult['message']);
            }
            Log::info('Admin user created successfully');

            // Mark as installed
            Log::info('Marking installation as complete');
            $this->installService->markAsInstalled();

            // Clear cache
            Log::info('Clearing cache');
            $this->installService->clearCache();

            // Clear session
            Log::info('Clearing session data');
            session()->forget('database_config');
            session()->forget('payment_config');
            session()->forget('admin_config');

            Log::info('Installation completed successfully - redirecting to complete page');
            return redirect()->route('install.complete');
        } catch (\Exception $e) {
            Log::error('Installation process failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'session_id' => session()->getId(),
                'has_database_config' => session()->has('database_config'),
                'has_admin_config' => session()->has('admin_config'),
                'has_payment_config' => session()->has('payment_config'),
                'session_keys' => array_keys(session()->all()),
            ]);
            // Save session before redirect to ensure data is preserved
            session()->save();
            // Redirect to payment page instead of back() to preserve session
            return redirect()->route('install.payment')
                ->with('error', 'An error occurred during installation: ' . $e->getMessage());
        }
    }

    /**
     * Show installation complete page
     */
    public function complete()
    {
        if (!$this->installService->isInstalled()) {
            return redirect()->route('install.index');
        }

        return view('install.complete');
    }

    /**
     * Check system requirements
     */
    private function checkRequirements(): array
    {
        $requirements = [
            'php_version' => [
                'name' => 'PHP Version >= 8.2',
                'required' => '8.2',
                'current' => PHP_VERSION,
                'status' => version_compare(PHP_VERSION, '8.2.0', '>='),
            ],
            'pdo' => [
                'name' => 'PDO Extension',
                'required' => true,
                'current' => extension_loaded('pdo'),
                'status' => extension_loaded('pdo'),
            ],
            'mbstring' => [
                'name' => 'Mbstring Extension',
                'required' => true,
                'current' => extension_loaded('mbstring'),
                'status' => extension_loaded('mbstring'),
            ],
            'openssl' => [
                'name' => 'OpenSSL Extension',
                'required' => true,
                'current' => extension_loaded('openssl'),
                'status' => extension_loaded('openssl'),
            ],
            'tokenizer' => [
                'name' => 'Tokenizer Extension',
                'required' => true,
                'current' => extension_loaded('tokenizer'),
                'status' => extension_loaded('tokenizer'),
            ],
            'xml' => [
                'name' => 'XML Extension',
                'required' => true,
                'current' => extension_loaded('xml'),
                'status' => extension_loaded('xml'),
            ],
            'ctype' => [
                'name' => 'Ctype Extension',
                'required' => true,
                'current' => extension_loaded('ctype'),
                'status' => extension_loaded('ctype'),
            ],
            'json' => [
                'name' => 'JSON Extension',
                'required' => true,
                'current' => extension_loaded('json'),
                'status' => extension_loaded('json'),
            ],
            'storage_writable' => [
                'name' => 'Storage Directory Writable',
                'required' => true,
                'current' => is_writable(storage_path()),
                'status' => is_writable(storage_path()),
            ],
            'cache_writable' => [
                'name' => 'Cache Directory Writable',
                'required' => true,
                'current' => is_writable(base_path('bootstrap/cache')),
                'status' => is_writable(base_path('bootstrap/cache')),
            ],
        ];

        return $requirements;
    }
}

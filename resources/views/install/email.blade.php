<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Configuration - System Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 1rem;
        }
        .install-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 3rem;
            max-width: 800px;
            width: 100%;
            margin: 2rem;
        }
        .install-logo {
            max-width: 100%;
            height: auto;
            width: 250px;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #102036 0%, #768BA9 100%);
            border: none;
            padding: 12px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            color: white;
            transition: transform 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            color: white;
        }
        .btn-secondary {
            border-radius: 50px;
            padding: 12px 40px;
            transition: transform 0.3s;
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
        }
        .form-control {
            border-radius: 10px;
        }
        .alert {
            border-radius: 10px;
        }
        .email-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .email-section h5 {
            color: #102036;
            margin-bottom: 1rem;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        /* Tablet and below */
        @media (max-width: 768px) {
            .install-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: 15px;
            }
            .install-logo {
                width: 200px;
            }
            .btn-primary, .btn-secondary {
                padding: 10px 30px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="install-card">
        <div class="text-center mb-4">
            <h2 class="mb-3">Email Configuration</h2>
            <p class="text-muted">Configure your email settings for sending notifications and emails</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('install.email.process') }}" id="emailForm" onsubmit="handleSubmit(event)">
            @csrf
            
            <div class="email-section">
                <h5><i class="bi bi-envelope me-2"></i>SMTP Configuration</h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mail_mailer" class="form-label">Mailer</label>
                        <select class="form-control" id="mail_mailer" name="mail_mailer" required>
                            <option value="smtp" {{ old('mail_mailer', 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ old('mail_mailer') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="mailgun" {{ old('mail_mailer') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ old('mail_mailer') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                        </select>
                        <div class="form-text">The mail driver to use</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="mail_host" class="form-label">SMTP Host</label>
                        <input type="text" class="form-control" id="mail_host" name="mail_host" 
                               value="{{ old('mail_host', 'mailhog') }}" required>
                        <div class="form-text">Your SMTP server hostname</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mail_port" class="form-label">SMTP Port</label>
                        <input type="number" class="form-control" id="mail_port" name="mail_port" 
                               value="{{ old('mail_port', '1025') }}" required>
                        <div class="form-text">SMTP server port (usually 587, 465, or 25)</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="mail_encryption" class="form-label">Encryption</label>
                        <select class="form-control" id="mail_encryption" name="mail_encryption">
                            <option value="null" {{ old('mail_encryption', 'null') == 'null' ? 'selected' : '' }}>None</option>
                            <option value="tls" {{ old('mail_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('mail_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                        <div class="form-text">Encryption method (TLS or SSL)</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mail_username" class="form-label">SMTP Username</label>
                        <input type="text" class="form-control" id="mail_username" name="mail_username" 
                               value="{{ old('mail_username', 'null') }}">
                        <div class="form-text">Leave empty or "null" if not required</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="mail_password" class="form-label">SMTP Password</label>
                        <input type="password" class="form-control" id="mail_password" name="mail_password" 
                               value="{{ old('mail_password', 'null') }}">
                        <div class="form-text">Leave empty or "null" if not required</div>
                    </div>
                </div>
            </div>
            
            <div class="email-section">
                <h5><i class="bi bi-person me-2"></i>From Address</h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mail_from_address" class="form-label">From Email Address</label>
                        <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" 
                               value="{{ old('mail_from_address', 'hello@example.com') }}" required>
                        <div class="form-text">The email address that emails will be sent from</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="mail_from_name" class="form-label">From Name</label>
                        <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" 
                               value="{{ old('mail_from_name', '${APP_NAME}') }}">
                        <div class="form-text">The name that will appear as the sender</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('install.payment') }}" class="btn btn-secondary" id="backBtn">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <div>
                    <button type="submit" name="skip" value="1" class="btn btn-outline-secondary me-2" id="skipBtn">
                        <i class="bi bi-skip-forward me-2"></i>Skip
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        Next<i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleSubmit(e) {
            const form = e.target;
            const submitBtn = document.getElementById('submitBtn');
            const skipBtn = document.getElementById('skipBtn');
            const backBtn = document.getElementById('backBtn');
            
            // Disable all buttons to prevent double submission
            submitBtn.disabled = true;
            skipBtn.disabled = true;
            if (backBtn) {
                backBtn.style.pointerEvents = 'none';
                backBtn.style.opacity = '0.6';
            }
            
            // Show loading state
            if (e.submitter === submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            } else if (e.submitter === skipBtn) {
                skipBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Skipping...';
            }
            
            // Handle null values
            const username = document.getElementById('mail_username');
            const password = document.getElementById('mail_password');
            const encryption = document.getElementById('mail_encryption');
            
            if (!username.value || username.value.trim() === '') {
                username.value = 'null';
            }
            if (!password.value || password.value.trim() === '') {
                password.value = 'null';
            }
            if (encryption.value === 'null') {
                encryption.value = 'null';
            }
        }
    </script>
</body>
</html>

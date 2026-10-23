<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب الأدمن - تثبيت النظام</title>
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
            max-width: 600px;
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
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s;
        }
        .form-control {
            border-radius: 10px;
        }
        .alert {
            border-radius: 10px;
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
        
        /* Mobile devices */
        @media (max-width: 576px) {
            body {
                padding: 0.5rem;
            }
            .install-card {
                padding: 1.5rem 1rem;
                margin: 0.5rem;
                border-radius: 12px;
            }
            .install-logo {
                width: 150px;
                margin-bottom: 1rem;
            }
            .install-card h2 {
                font-size: 1.5rem;
            }
            .btn-primary, .btn-secondary {
                padding: 10px 25px;
                font-size: 0.95rem;
                width: 100%;
                margin-bottom: 0.5rem;
            }
            .d-flex.justify-content-between {
                flex-direction: column;
            }
        }
        
        /* Very small devices */
        @media (max-width: 400px) {
            .install-card {
                padding: 1rem 0.75rem;
            }
            .install-logo {
                width: 120px;
            }
            .install-card h2 {
                font-size: 1.25rem;
            }
            .btn-primary, .btn-secondary {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="install-card">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/admin/images/logo/logo-dark.png') }}" alt="Perfect Logo" class="img-fluid install-logo">
            <h2><i class="bi bi-person-badge me-2"></i>إنشاء حساب الأدمن</h2>
            <p class="text-muted">قم بإنشاء حساب الأدمن الرئيسي للنظام</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form action="{{ route('install.admin.process') }}" method="POST" id="adminForm">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">الاسم الكامل</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                <div class="password-strength" id="passwordStrength"></div>
                <small class="text-muted">يجب أن تكون 8 أحرف على الأقل</small>
                @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="mb-3">
                <label for="frontend_url" class="form-label">رابط الواجهة الأمامية (Frontend URL)</label>
                <input type="url" class="form-control" id="frontend_url" name="frontend_url" value="{{ old('frontend_url', 'http://localhost:3000') }}" placeholder="http://localhost:3000" required>
                <small class="text-muted">أدخل رابط الواجهة الأمامية للتطبيق (مثال: http://localhost:3000 أو https://example.com)</small>
                @error('frontend_url')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>ملاحظة:</strong> سيتم إنشاء قاعدة البيانات وإنشاء جميع الجداول تلقائياً. قد تستغرق هذه العملية بضع دقائق.
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('install.database') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-right me-2"></i>السابق
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="spinner-border spinner-border-sm d-none me-2" id="spinner"></span>
                    التالي <i class="bi bi-arrow-left ms-2"></i>
                </button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Password strength indicator
            $('#password').on('input', function() {
                const password = $(this).val();
                const strength = calculatePasswordStrength(password);
                const strengthBar = $('#passwordStrength');
                
                strengthBar.css('width', strength.percentage + '%');
                strengthBar.removeClass('bg-danger bg-warning bg-success');
                
                if (strength.percentage < 50) {
                    strengthBar.addClass('bg-danger');
                } else if (strength.percentage < 75) {
                    strengthBar.addClass('bg-warning');
                } else {
                    strengthBar.addClass('bg-success');
                }
            });

            // Form submission
            $('#adminForm').on('submit', function() {
                $('#submitBtn').prop('disabled', true);
                $('#spinner').removeClass('d-none');
            });

            function calculatePasswordStrength(password) {
                let strength = 0;
                if (password.length >= 8) strength += 25;
                if (password.length >= 12) strength += 10;
                if (/[a-z]/.test(password)) strength += 15;
                if (/[A-Z]/.test(password)) strength += 15;
                if (/[0-9]/.test(password)) strength += 15;
                if (/[^A-Za-z0-9]/.test(password)) strength += 20;
                return { percentage: Math.min(strength, 100) };
            }
        });
    </script>
</body>
</html>

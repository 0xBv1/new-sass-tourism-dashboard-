<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطأ في التثبيت - تثبيت النظام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #102036 0%, #768BA9 100%);
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
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="install-card">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/admin/images/logo/logo-dark.png') }}" alt="Perfect Logo" class="img-fluid install-logo">
            <div class="error-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h2 class="text-danger">حدث خطأ</h2>
            <p class="text-muted">{{ $message ?? 'حدث خطأ أثناء التثبيت' }}</p>
        </div>

        @if(isset($error) && $error)
            <div class="alert alert-danger">
                <strong>تفاصيل الخطأ:</strong><br>
                <code>{{ $error }}</code>
            </div>
        @endif

        <div class="alert alert-info">
            <h5><i class="bi bi-info-circle me-2"></i>خطوات الحل:</h5>
            <ol class="mb-0">
                <li>تأكد من أن الخادم يعمل: <code>php artisan serve</code></li>
                <li>تحقق من ملفات السجلات: <code>storage/logs/laravel.log</code></li>
                <li>امسح الكاش: <code>php artisan optimize:clear</code></li>
                <li>تحقق من ملف .env ووجود APP_KEY</li>
                <li>راجع ملف TROUBLESHOOTING_CONNECTION_RESET.md للمزيد من الحلول</li>
            </ol>
        </div>

        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('install.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-right me-2"></i>العودة للصفحة الرئيسية
            </a>
            <a href="{{ route('install.requirements') }}" class="btn btn-outline-primary">
                <i class="bi bi-check-circle me-2"></i>التحقق من المتطلبات
            </a>
        </div>
    </div>
</body>
</html>

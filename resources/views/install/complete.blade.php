<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اكتمل التثبيت - تثبيت النظام</title>
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
            text-align: center;
        }
        .install-logo {
            max-width: 100%;
            height: auto;
            width: 250px;
            margin-bottom: 1.5rem;
        }
        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 4rem;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 12px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            color: white;
            transition: transform 0.3s;
            width: 100%;
            max-width: 300px;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            color: white;
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
            .success-icon {
                width: 100px;
                height: 100px;
                font-size: 3.5rem;
            }
            .install-card h1 {
                font-size: 1.75rem;
            }
            .btn-success {
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
            .success-icon {
                width: 80px;
                height: 80px;
                font-size: 3rem;
                margin-bottom: 1.5rem;
            }
            .install-card h1 {
                font-size: 1.5rem;
                margin-bottom: 1rem !important;
            }
            .install-card p {
                font-size: 0.95rem;
                margin-bottom: 1rem !important;
            }
            .btn-success {
                padding: 10px 25px;
                font-size: 0.95rem;
                max-width: 100%;
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
            .success-icon {
                width: 70px;
                height: 70px;
                font-size: 2.5rem;
            }
            .install-card h1 {
                font-size: 1.25rem;
            }
            .install-card p {
                font-size: 0.85rem;
            }
            .btn-success {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="install-card">
        <img src="{{ asset('assets/admin/images/logo/logo-dark.png') }}" alt="Perfect Logo" class="img-fluid install-logo">
        <div class="success-icon">
            <i class="bi bi-check-circle"></i>
        </div>
        <h1 class="mb-4 text-success">تم التثبيت بنجاح!</h1>
        <p class="text-muted mb-4">
            تم تثبيت النظام بنجاح. يمكنك الآن تسجيل الدخول باستخدام حساب الأدمن الذي قمت بإنشائه.
        </p>
        <div class="alert alert-info text-start">
            <h6><i class="bi bi-info-circle me-2"></i>خطوات مهمة:</h6>
            <ul class="mb-0">
                <li>احفظ معلومات تسجيل الدخول في مكان آمن</li>
                <li>قم بتغيير كلمة المرور بعد أول تسجيل دخول</li>
                <li>راجع إعدادات النظام من لوحة التحكم</li>
            </ul>
        </div>
        <a href="{{ route('login') }}" class="btn btn-success btn-lg">
            <i class="bi bi-box-arrow-in-right me-2"></i>تسجيل الدخول الآن
        </a>
    </div>
</body>
</html>

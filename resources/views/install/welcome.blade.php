<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك - تثبيت النظام</title>
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
            width: 300px;
            margin-bottom: 1.5rem;
        }
        .install-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #102036 0%, #768BA9 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 3rem;
        }
        .btn-install {
            background: linear-gradient(135deg, #102036 0%, #768BA9 100%);
            border: none;
            padding: 12px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            color: white;
            transition: transform 0.3s;
            width: 100%;
            max-width: 300px;
        }
        .btn-install:hover {
            transform: translateY(-2px);
            color: white;
        }
        .install-card h1 {
            font-size: 2rem;
        }
        .install-card h5 {
            font-size: 1.25rem;
        }
        .install-card ul {
            padding-right: 0;
        }
        
        /* Tablet and below */
        @media (max-width: 768px) {
            .install-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: 15px;
            }
            .install-logo {
                width: 250px;
            }
            .install-card h1 {
                font-size: 1.75rem;
            }
            .install-card h5 {
                font-size: 1.1rem;
            }
            .btn-install {
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
                width: 200px;
                margin-bottom: 1rem;
            }
            .install-card h1 {
                font-size: 1.5rem;
                margin-bottom: 1rem !important;
            }
            .install-card p {
                font-size: 0.95rem;
                margin-bottom: 1rem !important;
            }
            .install-card h5 {
                font-size: 1rem;
                margin-bottom: 0.75rem !important;
            }
            .install-card ul {
                font-size: 0.9rem;
            }
            .install-card ul li {
                margin-bottom: 0.5rem !important;
            }
            .install-card ul li i {
                font-size: 0.85rem;
            }
            .btn-install {
                padding: 10px 25px;
                font-size: 0.95rem;
                max-width: 100%;
            }
            .install-icon {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
                margin-bottom: 1.5rem;
            }
        }
        
        /* Very small devices */
        @media (max-width: 400px) {
            .install-card {
                padding: 1rem 0.75rem;
            }
            .install-logo {
                width: 150px;
            }
            .install-card h1 {
                font-size: 1.25rem;
            }
            .install-card p {
                font-size: 0.85rem;
            }
            .install-card ul {
                font-size: 0.85rem;
            }
            .btn-install {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="install-card text-center">
        <img src="{{ asset('assets/admin/images/logo/logo-dark.png') }}" alt="Perfect Logo" class="img-fluid install-logo">
        <h1 class="mb-4">Welcome to installation Dashboard</h1>
        <p class="text-muted mb-4">
            We will set up the system step by step. This process is quick and easy.
        </p>
        <div class="mb-4">
            <h5 class="mb-3">What we will do:</h5>
            <ul class="list-unstyled text-start text-md-start">
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Check the system requirements</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Set up the database</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Create the admin account</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Set up the basic system</li>
            </ul>
        </div>
        <a href="{{ route('install.requirements') }}" class="btn btn-install btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Start the Installation
        </a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحقق من المتطلبات - تثبيت النظام</title>
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
        .requirement-item {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        .requirement-item.passed {
            background: #d4edda;
            border-color: #28a745;
        }
        .requirement-item.failed {
            background: #f8d7da;
            border-color: #dc3545;
        }
        .btn-next {
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
        .btn-next:hover {
            color: white;
            transform: translateY(-2px);
        }
        .btn-next:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
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
            .install-card h2 {
                font-size: 1.75rem;
            }
            .btn-next {
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
                margin-bottom: 1rem !important;
            }
            .requirement-item {
                padding: 0.75rem;
            }
            .requirement-item strong {
                font-size: 0.95rem;
            }
            .requirement-item .small {
                font-size: 0.85rem;
            }
            .btn-next {
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
            .install-card h2 {
                font-size: 1.25rem;
            }
            .requirement-item {
                padding: 0.5rem;
            }
            .requirement-item strong {
                font-size: 0.9rem;
            }
            .btn-next {
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
            <h2><i class="bi bi-clipboard-check me-2"></i>التحقق من متطلبات النظام</h2>
            <p class="text-muted">يرجى التأكد من أن جميع المتطلبات التالية متوفرة</p>
        </div>

        <div class="requirements-list">
            @foreach($requirements as $key => $requirement)
            <div class="requirement-item {{ $requirement['status'] ? 'passed' : 'failed' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $requirement['name'] }}</strong>
                        <div class="text-muted small">
                            @if(isset($requirement['current']))
                                الحالي: {{ is_bool($requirement['current']) ? ($requirement['current'] ? 'نعم' : 'لا') : $requirement['current'] }}
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($requirement['status'])
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        @else
                            <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @php
            $allPassed = collect($requirements)->every(fn($req) => $req['status']);
        @endphp

        <div class="text-center mt-4">
            @if($allPassed)
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>جميع المتطلبات متوفرة! يمكنك المتابعة.
                </div>
                <a href="{{ route('install.database') }}" class="btn btn-next">
                    التالي <i class="bi bi-arrow-left ms-2"></i>
                </a>
            @else
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>يرجى إصلاح المتطلبات المفقودة قبل المتابعة.
                </div>
                <button class="btn btn-next" disabled>التالي <i class="bi bi-arrow-left ms-2"></i></button>
            @endif
        </div>
    </div>
</body>
</html>

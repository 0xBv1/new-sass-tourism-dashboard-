<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعداد قاعدة البيانات - تثبيت النظام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            max-width: 700px;
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
        .btn-outline-primary {
            border-radius: 50px;
            padding: 12px 40px;
            transition: transform 0.3s;
        }
        .btn-outline-primary:hover {
            transform: translateY(-2px);
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
            .btn-primary, .btn-secondary, .btn-outline-primary {
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
            .btn-primary, .btn-secondary, .btn-outline-primary {
                padding: 10px 25px;
                font-size: 0.95rem;
                width: 100%;
                margin-bottom: 0.5rem;
            }
            .d-flex.justify-content-between {
                flex-direction: column;
            }
            .d-flex.justify-content-between > div {
                width: 100%;
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
            .btn-primary, .btn-secondary, .btn-outline-primary {
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
            <h2><i class="bi bi-database me-2"></i>إعداد قاعدة البيانات</h2>
            <p class="text-muted">يرجى إدخال معلومات الاتصال بقاعدة البيانات</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form id="databaseForm" action="{{ route('install.database.process') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="host" class="form-label">اسم المضيف (Host)</label>
                <input type="text" class="form-control" id="host" name="host" value="{{ old('host', '127.0.0.1') }}" required>
                @error('host')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="port" class="form-label">المنفذ (Port)</label>
                <input type="text" class="form-control" id="port" name="port" value="{{ old('port', '3306') }}" required>
                @error('port')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="database" class="form-label">اسم قاعدة البيانات (Database)</label>
                <input type="text" class="form-control" id="database" name="database" value="{{ old('database') }}" required>
                @error('database')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">اسم المستخدم (Username)</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', 'root') }}" required>
                @error('username')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور (Password)</label>
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
                @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div id="testResult" class="alert d-none"></div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('install.requirements') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-right me-2"></i>السابق
                </a>
                <div>
                    <button type="button" id="testBtn" class="btn btn-outline-primary me-2">
                        <i class="bi bi-check-circle me-2"></i>اختبار الاتصال
                    </button>
                    <button type="submit" class="btn btn-primary">
                        التالي <i class="bi bi-arrow-left ms-2"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#testBtn').click(function() {
                const btn = $(this);
                const originalText = btn.html();
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>جاري الاختبار...');
                
                const formData = {
                    host: $('#host').val(),
                    port: $('#port').val(),
                    database: $('#database').val(),
                    username: $('#username').val(),
                    password: $('#password').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                $.ajax({
                    url: '{{ route("install.database.test") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        const alert = $('#testResult');
                        if (response.success) {
                            alert.removeClass('alert-danger d-none').addClass('alert-success')
                                .html('<i class="bi bi-check-circle me-2"></i>' + response.message);
                        } else {
                            alert.removeClass('alert-success d-none').addClass('alert-danger')
                                .html('<i class="bi bi-exclamation-triangle me-2"></i>' + response.message);
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        const alert = $('#testResult');
                        alert.removeClass('alert-success d-none').addClass('alert-danger')
                            .html('<i class="bi bi-exclamation-triangle me-2"></i>' + 
                                (response?.message || 'حدث خطأ أثناء الاتصال'));
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
</body>
</html>

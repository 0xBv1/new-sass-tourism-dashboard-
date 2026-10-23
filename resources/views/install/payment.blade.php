<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Setup - System Installation</title>
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
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s;
        }
        .payment-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .payment-section h5 {
            color: #102036;
            margin-bottom: 1rem;
        }
        .form-check-input:checked {
            background-color: #102036;
            border-color: #102036;
        }
        .key-input {
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
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
            <h2><i class="bi bi-credit-card me-2"></i>Payment Setup</h2>
            <p class="text-muted">Configure payment methods (Optional - You can skip this step)</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form action="{{ route('install.payment.process') }}" method="POST" id="paymentForm">
            @csrf
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="setup_payment" name="setup_payment" value="1" {{ old('setup_payment') ? 'checked' : '' }}>
                    <label class="form-check-label" for="setup_payment">
                        <strong>I want to setup payment methods now</strong>
                    </label>
                </div>
                <small class="text-muted">You can skip this step and configure payment methods later from the admin panel</small>
            </div>

            <div id="paymentFields" style="display: {{ old('setup_payment') ? 'block' : 'none' }};">
                <!-- PayPal Section -->
                <div class="payment-section">
                    <h5><i class="bi bi-paypal me-2"></i>PayPal</h5>
                    <div class="mb-3">
                        <label for="paypal_mode" class="form-label">PayPal Mode</label>
                        <select class="form-control" id="paypal_mode" name="paypal_mode">
                            <option value="sandbox" {{ old('paypal_mode', 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                            <option value="live" {{ old('paypal_mode') == 'live' ? 'selected' : '' }}>Live (Production)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="paypal_client_id" class="form-label">Client ID</label>
                        <input type="text" class="form-control key-input" id="paypal_client_id" name="paypal_client_id" value="{{ old('paypal_client_id') }}" placeholder="Enter PayPal Client ID">
                        @error('paypal_client_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="paypal_client_secret" class="form-label">Client Secret</label>
                        <input type="text" class="form-control key-input" id="paypal_client_secret" name="paypal_client_secret" value="{{ old('paypal_client_secret') }}" placeholder="Enter PayPal Client Secret">
                        @error('paypal_client_secret')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="paypal_app_id" class="form-label">App ID <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control key-input" id="paypal_app_id" name="paypal_app_id" value="{{ old('paypal_app_id') }}" placeholder="Enter PayPal App ID">
                    </div>
                </div>

                <!-- Fawaterk Section -->
                <div class="payment-section">
                    <h5><i class="bi bi-credit-card me-2"></i>Fawaterk (Card Payments)</h5>
                    <div class="mb-3">
                        <label for="fawaterak_base_url" class="form-label">Base URL</label>
                        <select class="form-control" id="fawaterak_base_url" name="fawaterak_base_url">
                            <option value="https://staging.fawaterk.com" {{ old('fawaterak_base_url', 'https://staging.fawaterk.com') == 'https://staging.fawaterk.com' ? 'selected' : '' }}>Staging (Testing)</option>
                            <option value="https://fawaterk.com" {{ old('fawaterak_base_url') == 'https://fawaterk.com' ? 'selected' : '' }}>Production</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fawaterak_api_key" class="form-label">API Key</label>
                        <input type="text" class="form-control key-input" id="fawaterak_api_key" name="fawaterak_api_key" value="{{ old('fawaterak_api_key') }}" placeholder="Enter Fawaterk API Key">
                        @error('fawaterak_api_key')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Note:</strong> You can skip this step and configure payment methods later from the system settings.
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('install.admin') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Previous
                </a>
                <div>
                    <button type="submit" name="skip" value="1" class="btn btn-secondary me-2">
                        Skip <i class="bi bi-forward ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="spinner"></span>
                        Next <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle payment fields visibility
            $('#setup_payment').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#paymentFields').slideDown();
                } else {
                    $('#paymentFields').slideUp();
                }
            });

            // Form submission
            $('#paymentForm').on('submit', function() {
                $('#submitBtn').prop('disabled', true);
                $('#spinner').removeClass('d-none');
            });
        });
    </script>
</body>
</html>

@php
    $logo = url('templates/dist/img/' . rawurlencode('Logo Help Desk Putih.png'));
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User - Helpdesk</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(180deg, #0c4a6e 0%, #164e63 50%, #155e75 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            font-size: 14px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 380px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            padding: 28px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .login-logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 6px;
        }

        .login-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .login-header h1 {
            font-size: 1.2em;
            font-weight: 700;
            color: #0c4a6e;
            margin-bottom: 4px;
            font-family: Arial, sans-serif;
        }

        .login-header p {
            font-size: 0.75em;
            color: #718096;
            font-weight: 400;
            font-family: Arial, sans-serif;
        }

        .alert {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.8em;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-family: Arial, sans-serif;
        }

        .alert-danger {
            background: #fef2f2;
            color: #c53030;
            border: 1px solid #fecaca;
        }

        .alert i {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            font-size: 0.8em;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 6px;
            font-family: Arial, sans-serif;
        }

        .form-group input {
            width: 100%;
            padding: 9px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9em;
            font-family: Arial, sans-serif;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
        }

        .form-group input.is-invalid {
            border-color: #f56565;
        }

        .error-message {
            font-size: 0.75em;
            color: #f56565;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-family: Arial, sans-serif;
        }

        .form-group .password-wrapper {
            position: relative;
        }

        .form-group .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            font-size: 0.9em;
            transition: color 0.3s ease;
        }

        .form-group .toggle-password:hover {
            color: #718096;
        }

        .login-button {
            width: 100%;
            padding: 10px;
            background: #06b6d4;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.9em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
            font-family: Arial, sans-serif;
        }

        .login-button:hover {
            background: #0891b2;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(6, 182, 212, 0.3);
        }

        .login-footer {
            text-align: center;
            margin-top: 16px;
            font-size: 0.8em;
            color: #718096;
            font-family: Arial, sans-serif;
        }

        .login-footer a {
            color: #06b6d4;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            font-family: Arial, sans-serif;
        }

        .login-footer a:hover {
            color: #0891b2;
            text-decoration: underline;
        }

        .login-divider {
            text-align: center;
            margin: 14px 0;
            font-size: 0.75em;
            color: #a0aec0;
            font-family: Arial, sans-serif;
        }

        .other-login {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .other-login a {
            padding: 9px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            color: #4a5568;
            font-size: 0.75em;
            font-weight: 500;
            transition: all 0.3s ease;
            font-family: Arial, sans-serif;
        }

        .other-login a:hover {
            border-color: #06b6d4;
            background: #f0f9fb;
            color: #06b6d4;
        }

        @media (max-width: 480px) {
            body {
                padding: 12px;
            }

            .login-card {
                padding: 20px;
            }

            .login-header h1 {
                font-size: 1.1em;
            }

            .form-group input {
                padding: 8px 10px;
                font-size: 0.85em;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <img src="{{ $logo }}" alt="Logo">
                </div>
                <h1>Login User</h1>
                <p>Masukkan kredensial Anda</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login_user.post') }}">
                @csrf

                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username"
                        class="@error('username') is-invalid @enderror"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username Anda"
                        required
                    >
                    @error('username')
                        <div class="error-message">
                            <i class="fas fa-times-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="@error('password') is-invalid @enderror"
                            placeholder="Masukkan password Anda"
                            required
                        >
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                    </div>
                    @error('password')
                        <div class="error-message">
                            <i class="fas fa-times-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="login-button">Login</button>
            </form>

            <div class="login-divider">atau</div>

            <div class="other-login">
                <a href="{{ route('register_user') }}">📝 Daftar</a>
                <a href="{{ route('login_petugas') }}">🔐 Login Petugas</a>
            </div>

                        <div class="login-footer">
                Kembali ke <a href="{{ route('home') }}">Beranda</a>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.querySelector(`[onclick="togglePassword('${fieldId}')"]`);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

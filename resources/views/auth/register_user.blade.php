@php
    $logo = url('templates/dist/img/' . rawurlencode('Logo Help Desk Putih.png'));
    $departemen = \App\Models\Departemen::pluck('nama_departemen', 'id');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User - Helpdesk</title>
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

        .register-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            padding: 28px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .register-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .register-logo {
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

        .register-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .register-header h1 {
            font-size: 1.2em;
            font-weight: 700;
            color: #0c4a6e;
            margin-bottom: 4px;
            font-family: Arial, sans-serif;
        }

        .register-header p {
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
            margin-bottom: 12px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .form-row-full {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            font-size: 0.75em;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 4px;
            font-family: Arial, sans-serif;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px 10px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.85em;
            font-family: Arial, sans-serif;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-group input.is-invalid,
        .form-group select.is-invalid {
            border-color: #f56565;
        }

        .error-message {
            font-size: 0.7em;
            color: #f56565;
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 3px;
            font-family: Arial, sans-serif;
        }

        .form-group .password-wrapper {
            position: relative;
        }

        .form-group .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            font-size: 0.85em;
            transition: color 0.3s ease;
        }

        .form-group .toggle-password:hover {
            color: #718096;
        }

        .register-button {
            width: 100%;
            padding: 10px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.9em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
            font-family: Arial, sans-serif;
        }

        .register-button:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }

        .register-footer {
            text-align: center;
            margin-top: 14px;
            font-size: 0.8em;
            color: #718096;
            font-family: Arial, sans-serif;
        }

        .register-footer a {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            font-family: Arial, sans-serif;
        }

        .register-footer a:hover {
            color: #059669;
            text-decoration: underline;
        }

        .register-divider {
            text-align: center;
            margin: 12px 0;
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
            padding: 8px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            color: #4a5568;
            font-size: 0.7em;
            font-weight: 500;
            transition: all 0.3s ease;
            font-family: Arial, sans-serif;
        }

        .other-login a:hover {
            border-color: #10b981;
            background: #f0fdf4;
            color: #10b981;
        }

        @media (max-width: 480px) {
            body {
                padding: 12px;
            }

            .register-card {
                padding: 20px;
            }

            .register-header h1 {
                font-size: 1.1em;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .form-row-full {
                grid-column: 1;
            }

            .form-group input,
            .form-group select {
                padding: 7px 9px;
                font-size: 0.8em;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-card">
            <!-- Header -->
            <div class="register-header">
                <div class="register-logo">
                    <img src="{{ $logo }}" alt="Logo">
                </div>
                <h1>Daftar User</h1>
                <p>Buat akun baru Anda</p>
            </div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Form Register -->
            <form method="POST" action="{{ route('register_user.post') }}">
                @csrf

                <!-- NIK & Username -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input 
                            type="text" 
                            id="nik" 
                            name="nik"
                            class="@error('nik') is-invalid @enderror"
                            value="{{ old('nik') }}"
                            placeholder="NIK"
                            required
                        >
                        @error('nik')
                            <div class="error-message">
                                <i class="fas fa-times-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username"
                            class="@error('username') is-invalid @enderror"
                            value="{{ old('username') }}"
                            placeholder="Username"
                            required
                        >
                        @error('username')
                            <div class="error-message">
                                <i class="fas fa-times-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div class="form-group form-row-full">
                    <label for="nama">Nama Lengkap</label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama"
                        class="@error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}"
                        placeholder="Nama Lengkap"
                        required
                    >
                    @error('nama')
                        <div class="error-message">
                            <i class="fas fa-times-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group form-row-full">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        class="@error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Email Anda"
                        required
                    >
                    @error('email')
                        <div class="error-message">
                            <i class="fas fa-times-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- No. Telpon & Departemen -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="no_telepon">No. Telepon</label>
                        <input 
                            type="tel" 
                            id="no_telepon" 
                            name="no_telepon"
                            class="@error('no_telepon') is-invalid @enderror"
                            value="{{ old('no_telepon') }}"
                            placeholder="08xxxxxxxxxx"
                        >
                        @error('no_telepon')
                            <div class="error-message">
                                <i class="fas fa-times-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="departemen_id">Departemen</label>
                        <select 
                            id="departemen_id" 
                            name="departemen_id"
                            class="@error('departemen_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Pilih Departemen</option>
                            @foreach($departemen as $id => $nama)
                                <option value="{{ $id }}" @selected(old('departemen_id') == $id)>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('departemen_id')
                            <div class="error-message">
                                <i class="fas fa-times-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Password & Confirm Password -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="@error('password') is-invalid @enderror"
                                placeholder="Minimal 6 karakter"
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

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="password-wrapper">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                placeholder="Ulangi Password"
                                required
                            >
                            <i class="fas fa-eye toggle-password" onclick="togglePassword('password_confirmation')"></i>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="register-button">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>

            <!-- Divider -->
            <div class="register-divider" data-text="atau"></div>

            <!-- Alternative Login -->
            <div class="other-login">
                <a href="{{ route('login_user') }}">
                    🔓 Login User
                </a>
                <a href="{{ route('login_petugas') }}">
                    🔐 Login Petugas
                </a>
            </div>

            <!-- Footer -->
            <div class="register-footer">
                Kembali ke <a href="{{ route('home') }}">Beranda</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

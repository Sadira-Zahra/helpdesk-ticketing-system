@extends('layouts.main')

@section('title', 'Ganti Profil - Helpdesk')

@section('page-title', 'Ganti Profil')

@section('content')
<div class="container-fluid">
     <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <div class="row">
        <!-- Card Profil -->
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center" style="padding: 20px;">
                    @php
                        $initial = strtoupper(substr($user->nama ?? 'U', 0, 1));
                        $avatarFallback = 'https://via.placeholder.com/120/667eea/ffffff?text=' . urlencode($initial);
                        $avatarSrc = $user->photo ? asset('storage/' . $user->photo) : $avatarFallback;
                    @endphp
                    <img src="{{ $avatarSrc }}" alt="{{ $user->nama }}" class="rounded-circle mb-2" 
                         style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #667eea;">
                    <h6 class="mb-1" style="font-weight: 600; font-size: 15px;">{{ $user->nama }}</h6>
                    <p class="text-muted mb-2" style="font-size: 12px;">{{ ucfirst($user->role) }}</p>
                    @if($user->role !== 'administrator' && $user->departemen)
                        <span class="badge bg-primary" style="font-size: 11px;">{{ $user->departemen->nama_departemen }}</span>
                    @endif
                    <hr style="margin: 15px 0;">
                    <div class="text-start" style="font-size: 12px;">
                        <p class="mb-1"><i class="fas fa-id-card me-2" style="width: 18px;"></i> <strong>NIK:</strong> {{ $user->nik }}</p>
                        <p class="mb-1"><i class="fas fa-user me-2" style="width: 18px;"></i> <strong>Username:</strong> {{ $user->username }}</p>
                        <p class="mb-1"><i class="fas fa-envelope me-2" style="width: 18px;"></i> <strong>Email:</strong> {{ $user->email }}</p>
                        <p class="mb-0"><i class="fas fa-phone me-2" style="width: 18px;"></i> <strong>Telepon:</strong> {{ $user->no_telepon ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit Profil -->
        <div class="col-lg-8">
            <!-- Edit Data Profil -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom" style="padding: 15px;">
                    <h6 class="mb-0" style="font-size: 14px; font-weight: 600;"><i class="fas fa-user-edit"></i> Edit Data Profil</h6>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <form action="{{ route('ganti_profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- NIK -->
                            <div class="col-md-6 mb-2">
                                <label for="nik" class="form-label" style="font-weight: 500; color: #555; font-size: 12px; margin-bottom: 5px;">
                                    NIK <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                                       id="nik" name="nik" value="{{ old('nik', $user->nik) }}" required
                                       style="font-size: 13px; padding: 7px 10px;">
                                @error('nik')
                                    <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Username -->
                            <div class="col-md-6 mb-2">
                                <label for="username" class="form-label" style="font-weight: 500; color: #555; font-size: 12px; margin-bottom: 5px;">
                                    Username <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username', $user->username) }}" required
                                       style="font-size: 13px; padding: 7px 10px;">
                                @error('username')
                                    <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Nama -->
                        <div class="mb-2">
                            <label for="nama" class="form-label" style="font-weight: 500; color: #555; font-size: 12px; margin-bottom: 5px;">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                                   style="font-size: 13px; padding: 7px 10px;">
                            @error('nama')
                                <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-2">
                            <label for="email" class="form-label" style="font-weight: 500; color: #555; font-size: 12px; margin-bottom: 5px;">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   style="font-size: 13px; padding: 7px 10px;">
                            @error('email')
                                <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- No Telepon -->
                        <div class="mb-2">
                            <label for="no_telepon" class="form-label" style="font-weight: 500; color: #555; font-size: 12px; margin-bottom: 5px;">
                                No. Telepon
                            </label>
                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                   id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}"
                                   style="font-size: 13px; padding: 7px 10px;">
                            @error('no_telepon')
                                <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Departemen (hanya untuk non-administrator) -->
                        @if($user->role !== 'administrator')
                            <div class="mb-2">
                                <label for="departemen_id" class="form-label" style="font-weight: 500; color: #555; font-size: 12px; margin-bottom: 5px;">
                                    Departemen <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('departemen_id') is-invalid @enderror" 
                                        id="departemen_id" name="departemen_id" required
                                        style="font-size: 13px; padding: 7px 10px;">
                                    <option value="">-- Pilih Departemen --</option>
                                    @foreach($departemens as $departemen)
                                        <option value="{{ $departemen->id }}" 
                                            {{ old('departemen_id', $user->departemen_id) == $departemen->id ? 'selected' : '' }}>
                                            {{ $departemen->nama_departemen }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departemen_id')
                                    <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <!-- Foto -->
                        <div class="mb-3">
                            <label for="photo" class="form-label" style="font-weight: 500; color: #555; font-size: 12px; margin-bottom: 5px;">
                                Foto Profil
                            </label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" name="photo" accept="image/*"
                                   style="font-size: 12px; padding: 6px 10px;">
                            <small class="text-muted" style="font-size: 11px;">Maksimal 2MB, format: JPEG, PNG, JPG, GIF</small>
                            @error('photo')
                                <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                            @enderror
                            <div id="photoPreview" class="mt-2"></div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm" style="font-size: 13px; padding: 6px 15px;">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ganti Password -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom" style="padding: 15px;">
                    <h6 class="mb-0" style="font-size: 14px; font-weight: 600;"><i class="fas fa-key"></i> Ganti Password</h6>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <form action="{{ route('ganti_profil.update_password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Password Lama -->
                        <div class="mb-2">
                            <label for="current_password" class="form-label" style="font-weight: 500; color: #555; font-size: 12px; margin-bottom: 5px;">
                                Password Lama <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required
                                   style="font-size: 13px; padding: 7px 10px;">
                            @error('current_password')
                                <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label" style="font-weight: 500; color: #555; font-size: 12px; margin-bottom: 5px;">
                                    Password Baru <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" name="new_password" required
                                       style="font-size: 13px; padding: 7px 10px;">
                                <small class="text-muted" style="font-size: 11px;">Minimal 6 karakter</small>
                                @error('new_password')
                                    <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="new_password_confirmation" class="form-label" style="font-weight: 500; color: #555; font-size: 12px; margin-bottom: 5px;">
                                    Konfirmasi Password Baru <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" 
                                       id="new_password_confirmation" name="new_password_confirmation" required
                                       style="font-size: 13px; padding: 7px 10px;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning btn-sm" style="font-size: 13px; padding: 6px 15px;">
                                <i class="fas fa-key"></i> Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
@section('scripts')
<script>
    // Preview foto
    document.getElementById('photo').addEventListener('change', function(e) {
        const preview = document.getElementById('photoPreview');
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = (event) => {
                preview.innerHTML = `<img src="${event.target.result}" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">`;
            };
            reader.readAsDataURL(e.target.files[0]);
        } else {
            preview.innerHTML = '';
        }
    });

    // Auto dismiss alert setelah 5 detik
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endsection
@endsection

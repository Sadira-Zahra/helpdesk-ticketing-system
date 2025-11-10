@extends('layouts.main')

@section('title', 'Master User - Helpdesk')

@section('page-title', 'Master User')

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <!-- Table Data User -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center" style="padding: 20px;">
            <h5 class="mb-0">Daftar User</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus"></i> Tambah User
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background-color: #f5f7fa;">
                            <th style="width: 50px; color: #666; font-weight: 600;">No</th>
                            <th style="width: 80px; color: #666; font-weight: 600;">Foto</th>
                            <th style="color: #666; font-weight: 600;">NIK</th>
                            <th style="color: #666; font-weight: 600;">Username</th>
                            <th style="color: #666; font-weight: 600;">Nama</th>
                            <th style="color: #666; font-weight: 600;">Email</th>
                            <th style="color: #666; font-weight: 600;">Departemen</th>
                            <th style="width: 120px; color: #666; font-weight: 600;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @php
                                $initial = strtoupper(substr($user->nama ?? 'U', 0, 1));
                                $avatarFallback = 'https://via.placeholder.com/40/667eea/ffffff?text=' . urlencode($initial);
                                $avatarSrc = $user->photo ? asset('storage/' . $user->photo) : $avatarFallback;
                            @endphp
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->index + 1 }}</td>
                                <td>
                                    <img src="{{ $avatarSrc }}" alt="{{ $user->nama }}" class="rounded-circle" 
                                         style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #667eea;">
                                </td>
                                <td>{{ $user->nik ?? '-' }}</td>
                                <td><strong>{{ $user->username ?? '-' }}</strong></td>
                                <td>{{ $user->nama ?? '-' }}</td>
                                <td>{{ $user->email ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $user->departemen->nama_departemen ?? '-' }}</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" 
                                            onclick="editUser({{ $user->id }})"
                                            data-bs-toggle="modal" data-bs-target="#editUserModal"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $user->id }}, '{{ $user->nama }}')"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd;"></i><br>
                                    <span class="text-muted">Belum ada data user</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ============ MODAL TAMBAH USER ============ -->
<div class="modal fade" id="addUserModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content" style="background-color: white; border: 1px solid #e9ecef;">
            <div class="modal-header" style="background: white; border-bottom: 1px solid #e9ecef; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 600; color: #2c3e50;">
                    <i class="fas fa-user-plus"></i> Tambah User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="opacity: 0.5;"></button>
            </div>
            <form id="addUserForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="padding: 20px;">
                    <!-- NIK -->
                    <div class="mb-3">
                        <label for="addNik" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            NIK <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="addNik" name="nik" 
                               placeholder="Masukkan NIK" required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="addNikError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="addUsername" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Username <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="addUsername" name="username" 
                               placeholder="Masukkan username" required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="addUsernameError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="addNama" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Nama <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="addNama" name="nama" 
                               placeholder="Masukkan nama" required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="addNamaError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="addEmail" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" id="addEmail" name="email" 
                               placeholder="Masukkan email" required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="addEmailError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- No Telepon -->
                    <div class="mb-3">
                        <label for="addTelepon" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            No. Telepon
                        </label>
                        <input type="text" class="form-control" id="addTelepon" name="no_telepon" 
                               placeholder="Masukkan no. telepon" style="font-size: 13px; padding: 8px 12px;">
                    </div>

                    <!-- Departemen REQUIRED -->
                    <div class="mb-3">
                        <label for="addDepartemen" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Departemen <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" id="addDepartemen" name="departemen_id" required style="font-size: 13px; padding: 8px 12px;">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departemens as $departemen)
                                <option value="{{ $departemen->id }}">{{ $departemen->nama_departemen }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="addDepartemenError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- Password -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="addPassword" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                                Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="addPassword" name="password" 
                                   placeholder="Minimal 6 karakter" required style="font-size: 13px; padding: 8px 12px;">
                            <div class="invalid-feedback" id="addPasswordError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="addPasswordConfirm" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                                Konfirmasi <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="addPasswordConfirm" 
                                   name="password_confirmation" placeholder="Ulangi password" required style="font-size: 13px; padding: 8px 12px;">
                        </div>
                    </div>

                    <!-- Foto -->
                    <div class="mb-3">
                        <label for="addPhoto" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Foto
                        </label>
                        <input type="file" class="form-control" id="addPhoto" name="photo" 
                               accept="image/*" style="font-size: 13px; padding: 8px 12px;">
                        <small class="text-muted" style="font-size: 12px;">Maksimal 2MB, format: JPEG, PNG, JPG, GIF</small>
                        <div id="addPhotoPreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer" style="background: white; border-top: 1px solid #e9ecef; padding: 15px 20px;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ MODAL EDIT USER ============ -->
<div class="modal fade" id="editUserModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content" style="background-color: white; border: 1px solid #e9ecef;">
            <div class="modal-header" style="background: white; border-bottom: 1px solid #e9ecef; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 600; color: #2c3e50;">
                    <i class="fas fa-user-edit"></i> Edit User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="opacity: 0.5;"></button>
            </div>
            <form id="editUserForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId">
                <div class="modal-body" style="padding: 20px;">
                    <!-- NIK -->
                    <div class="mb-3">
                        <label for="editNik" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            NIK <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="editNik" name="nik" required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="editNikError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="editUsername" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Username <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="editUsername" name="username" required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="editUsernameError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="editNama" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Nama <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="editNama" name="nama" required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="editNamaError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="editEmail" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" id="editEmail" name="email" required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="editEmailError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- No Telepon -->
                    <div class="mb-3">
                        <label for="editTelepon" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            No. Telepon
                        </label>
                        <input type="text" class="form-control" id="editTelepon" name="no_telepon" style="font-size: 13px; padding: 8px 12px;">
                    </div>

                    <!-- Departemen REQUIRED -->
                    <div class="mb-3">
                        <label for="editDepartemen" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Departemen <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" id="editDepartemen" name="departemen_id" required style="font-size: 13px; padding: 8px 12px;">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departemens as $departemen)
                                <option value="{{ $departemen->id }}">{{ $departemen->nama_departemen }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="editDepartemenError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- Password -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editPassword" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                                Password Baru
                            </label>
                            <input type="password" class="form-control" id="editPassword" name="password" 
                                   placeholder="Kosongkan jika tidak diubah" style="font-size: 13px; padding: 8px 12px;">
                            <small class="text-muted" style="font-size: 12px;">Minimal 6 karakter</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editPasswordConfirm" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                                Konfirmasi Password
                            </label>
                            <input type="password" class="form-control" id="editPasswordConfirm" 
                                   name="password_confirmation" placeholder="Ulangi password" style="font-size: 13px; padding: 8px 12px;">
                        </div>
                    </div>

                    <!-- Foto -->
                    <div class="mb-3">
                        <label for="editPhoto" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Foto
                        </label>
                        <input type="file" class="form-control" id="editPhoto" name="photo" accept="image/*" style="font-size: 13px; padding: 8px 12px;">
                        <small class="text-muted" style="font-size: 12px;">Maksimal 2MB, format: JPEG, PNG, JPG, GIF</small>
                        <div id="editPhotoPreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer" style="background: white; border-top: 1px solid #e9ecef; padding: 15px 20px;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-info">
                        <i class="fas fa-save"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ JAVASCRIPT ============ -->
@section('scripts')
<script>
    const baseUrl = "{{ route('user.index') }}";
    const csrfToken = "{{ csrf_token() }}";

    // Preview foto pada modal tambah
    document.getElementById('addPhoto').addEventListener('change', function(e) {
        const preview = document.getElementById('addPhotoPreview');
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = (event) => {
                preview.innerHTML = `<img src="${event.target.result}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">`;
            };
            reader.readAsDataURL(e.target.files[0]);
        } else {
            preview.innerHTML = '';
        }
    });

    // Preview foto pada modal edit
    document.getElementById('editPhoto').addEventListener('change', function(e) {
        const preview = document.getElementById('editPhotoPreview');
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = (event) => {
                preview.innerHTML = `<img src="${event.target.result}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">`;
            };
            reader.readAsDataURL(e.target.files[0]);
        } else {
            preview.innerHTML = '';
        }
    });

    // Clear form error saat modal dibuka
    document.getElementById('addUserModal').addEventListener('show.bs.modal', function() {
        clearFormErrors('add');
        document.getElementById('addUserForm').reset();
        document.getElementById('addPhotoPreview').innerHTML = '';
    });

    document.getElementById('editUserModal').addEventListener('show.bs.modal', function() {
        clearFormErrors('edit');
    });

    // Handle form submit untuk TAMBAH
    document.getElementById('addUserForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        
        try {
            const response = await fetch("{{ route('user.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showAlert('success', data.message);
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                modal.hide();

                document.getElementById('addUserForm').reset();
                document.getElementById('addPhotoPreview').innerHTML = '';

                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                if (data.errors) {
                    displayErrors('add', data.errors);
                } else {
                    showAlert('error', data.message || 'Terjadi kesalahan saat menyimpan data');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat menyimpan data');
        }
    });

    // Function edit user
    function editUser(id) {
        fetch(`{{ url('user') }}/${id}`)
            .then(response => {
                if (!response.ok) throw new Error('Data tidak ditemukan');
                return response.json();
            })
            .then(data => {
                document.getElementById('editUserId').value = data.id;
                document.getElementById('editNik').value = data.nik;
                document.getElementById('editUsername').value = data.username;
                document.getElementById('editNama').value = data.nama;
                document.getElementById('editEmail').value = data.email;
                document.getElementById('editTelepon').value = data.no_telepon;
                document.getElementById('editDepartemen').value = data.departemen_id || '';
                
                if (data.photo_url) {
                    document.getElementById('editPhotoPreview').innerHTML = 
                        `<img src="${data.photo_url}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">`;
                } else {
                    document.getElementById('editPhotoPreview').innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Gagal memuat data user');
            });
    }

    // Handle form submit untuk EDIT
    document.getElementById('editUserForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const userId = document.getElementById('editUserId').value;
        const formData = new FormData(e.target);

        try {
            const response = await fetch(`{{ url('user') }}/${userId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showAlert('success', data.message);
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                modal.hide();

                document.getElementById('editUserForm').reset();
                document.getElementById('editPhotoPreview').innerHTML = '';

                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                if (data.errors) {
                    displayErrors('edit', data.errors);
                } else {
                    showAlert('error', data.message || 'Terjadi kesalahan saat memperbarui data');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memperbarui data');
        }
    });

    // Function confirm delete
    function confirmDelete(id, name) {
        if (confirm(`Apakah Anda yakin ingin menghapus user "${name}"?`)) {
            deleteUser(id);
        }
    }

    // Function delete user
    function deleteUser(id) {
        fetch(`{{ url('user') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan saat menghapus data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat menghapus data');
        });
    }

    // Function untuk display error messages
    function displayErrors(prefix, errors) {
        clearFormErrors(prefix);
        Object.keys(errors).forEach(field => {
            const fieldName = field.replace(/_/g, '');
            const capitalizedFieldName = fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
            const errorElement = document.getElementById(`${prefix}${capitalizedFieldName}Error`);
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.style.display = 'block';
            }
        });
    }

    // Function untuk clear error messages
    function clearFormErrors(prefix) {
        document.querySelectorAll(`#${prefix}UserForm .invalid-feedback`).forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });
    }

    // Function untuk show alert
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
                <i class="fas ${iconClass}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const alertContainer = document.getElementById('alertContainer');
        const alertDiv = document.createElement('div');
        alertDiv.innerHTML = alertHtml;
        alertContainer.insertBefore(alertDiv.firstElementChild, alertContainer.firstChild);

        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
</script>
@endsection
@endsection

@extends('layouts.main')

@section('title', 'Master Departemen - Helpdesk')

@section('page-title', 'Master Departemen')

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <!-- Table Data Departemen -->
    <div class="card border-0 shadow-sm">
        <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center" style="padding: 20px;">
        <h5 class="mb-0">Daftar Departemen</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartemenModal">
            <i class="fas fa-plus"></i> Tambah Departemen
        </button>
    </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background-color: #f5f7fa;">
                            <th style="width: 80px; color: #666; font-weight: 600;">No</th>
                            <th style="color: #666; font-weight: 600;">Nama Departemen</th>
                            <th style="width: 120px; color: #666; font-weight: 600;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departemens as $departemen)
                            <tr>
                                <td>{{ ($departemens->currentPage() - 1) * $departemens->perPage() + $loop->index + 1 }}</td>
                                <td><strong>{{ $departemen->nama_departemen }}</strong></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" 
                                            onclick="editDepartemen({{ $departemen->id }})"
                                            data-bs-toggle="modal" data-bs-target="#editDepartemenModal"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $departemen->id }}, '{{ $departemen->nama_departemen }}')"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd;"></i><br>
                                    <span class="text-muted">Belum ada data departemen</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($departemens->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $departemens->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ============ MODAL TAMBAH DEPARTEMEN ============ -->
<div class="modal fade" id="addDepartemenModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="background-color: white; border: 1px solid #e9ecef;">
            <div class="modal-header" style="background: white; border-bottom: 1px solid #e9ecef; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 600; color: #2c3e50;">
                    <i class="fas fa-sitemap"></i> Tambah Departemen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="opacity: 0.5;"></button>
            </div>
            <form id="addDepartemenForm">
                @csrf
                <div class="modal-body" style="padding: 20px;">
                    <!-- Nama Departemen -->
                    <div class="mb-3">
                        <label for="addNamaDepartemen" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Nama Departemen <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="addNamaDepartemen" name="nama_departemen" 
                               placeholder="Masukkan nama departemen" required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="namaDepartemenError" style="font-size: 12px;"></div>
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

<!-- ============ MODAL EDIT DEPARTEMEN ============ -->
<div class="modal fade" id="editDepartemenModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="background-color: white; border: 1px solid #e9ecef;">
            <div class="modal-header" style="background: white; border-bottom: 1px solid #e9ecef; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 600; color: #2c3e50;">
                    <i class="fas fa-edit"></i> Edit Departemen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="opacity: 0.5;"></button>
            </div>
            <form id="editDepartemenForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editDepartemenId">
                <div class="modal-body" style="padding: 20px;">
                    <!-- Nama Departemen -->
                    <div class="mb-3">
                        <label for="editNamaDepartemen" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Nama Departemen <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="editNamaDepartemen" name="nama_departemen" 
                               required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="editNamaDepartemenError" style="font-size: 12px;"></div>
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
    const baseUrl = "{{ route('departemen.index') }}";
    const csrfToken = "{{ csrf_token() }}";

    // Clear form error saat modal dibuka
    document.getElementById('addDepartemenModal').addEventListener('show.bs.modal', function() {
        clearFormErrors('add');
    });

    document.getElementById('editDepartemenModal').addEventListener('show.bs.modal', function() {
        clearFormErrors('edit');
    });

    // Handle form submit untuk TAMBAH
    document.getElementById('addDepartemenForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        
        try {
            const response = await fetch("{{ route('departemen.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showAlert('success', data.message);
                
                // Tutup modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addDepartemenModal'));
                modal.hide();

                // Reset form
                document.getElementById('addDepartemenForm').reset();

                // Reload halaman setelah 1.5 detik
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

    // Function edit departemen
    function editDepartemen(id) {
        fetch(`{{ url('departemen') }}/${id}`)
            .then(response => {
                if (!response.ok) throw new Error('Data tidak ditemukan');
                return response.json();
            })
            .then(data => {
                document.getElementById('editDepartemenId').value = data.id;
                document.getElementById('editNamaDepartemen').value = data.nama_departemen;
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Gagal memuat data departemen');
            });
    }

    // Handle form submit untuk EDIT
    document.getElementById('editDepartemenForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const departemenId = document.getElementById('editDepartemenId').value;
        const formData = new FormData(e.target);

        try {
            const response = await fetch(`{{ url('departemen') }}/${departemenId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showAlert('success', data.message);
                
                // Tutup modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editDepartemenModal'));
                modal.hide();

                // Reset form
                document.getElementById('editDepartemenForm').reset();

                // Reload halaman setelah 1.5 detik
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
        if (confirm(`Apakah Anda yakin ingin menghapus departemen "${name}"?`)) {
            deleteDepartemen(id);
        }
    }

    // Function delete departemen
    function deleteDepartemen(id) {
        fetch(`{{ url('departemen') }}/${id}`, {
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
            const errorElement = document.getElementById(`${prefix}${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)}Error`);
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.style.display = 'block';
            }
        });
    }

    // Function untuk clear error messages
    function clearFormErrors(prefix) {
        document.querySelectorAll(`#${prefix}DepartemenForm .invalid-feedback`).forEach(el => {
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

        // Auto dismiss alert setelah 5 detik
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

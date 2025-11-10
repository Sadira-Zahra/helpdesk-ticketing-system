@extends('layouts.main')

@section('title', 'Master Urgency - Helpdesk')

@section('page-title', 'Master Urgency')

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <!-- Table Data Urgency -->
    <div class="card border-0 shadow-sm">
       <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center" style="padding: 20px;">
        <h5 class="mb-0">Daftar Urgency</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUrgencyModal">
            <i class="fas fa-plus"></i> Tambah Urgency
        </button>
    </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background-color: #f5f7fa;">
                            <th style="width: 80px; color: #666; font-weight: 600;">No</th>
                            <th style="color: #666; font-weight: 600;">Urgency</th>
                            <th style="width: 150px; color: #666; font-weight: 600;">Jam SLA</th>
                            <th style="width: 120px; color: #666; font-weight: 600;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($urgencies as $urgency)
                            <tr>
                                <td>{{ ($urgencies->currentPage() - 1) * $urgencies->perPage() + $loop->index + 1 }}</td>
                                <td><strong>{{ $urgency->urgency }}</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $urgency->jam }} jam</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" 
                                            onclick="editUrgency({{ $urgency->id }})"
                                            data-bs-toggle="modal" data-bs-target="#editUrgencyModal"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $urgency->id }}, '{{ $urgency->urgency }}')"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd;"></i><br>
                                    <span class="text-muted">Belum ada data urgency</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($urgencies->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $urgencies->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ============ MODAL TAMBAH URGENCY ============ -->
<div class="modal fade" id="addUrgencyModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="background-color: white; border: 1px solid #e9ecef;">
            <div class="modal-header" style="background: white; border-bottom: 1px solid #e9ecef; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 600; color: #2c3e50;">
                    <i class="fas fa-exclamation-circle"></i> Tambah Urgency
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="opacity: 0.5;"></button>
            </div>
            <form id="addUrgencyForm">
                @csrf
                <div class="modal-body" style="padding: 20px;">
                    <!-- Urgency -->
                    <div class="mb-3">
                        <label for="addUrgency" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Urgency <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="addUrgency" name="urgency" 
                               placeholder="Contoh: High, Medium, Low" required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="addUrgencyError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- Jam SLA -->
                    <div class="mb-3">
                        <label for="addJam" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Jam SLA <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="addJam" name="jam" 
                               placeholder="Contoh: 24, 48, 72" min="1" max="9999" required style="font-size: 13px; padding: 8px 12px;">
                        <small class="text-muted" style="font-size: 12px;">Target SLA dalam jam</small>
                        <div class="invalid-feedback" id="addJamError" style="font-size: 12px; display: none; color: #dc3545;"></div>
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

<!-- ============ MODAL EDIT URGENCY ============ -->
<div class="modal fade" id="editUrgencyModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="background-color: white; border: 1px solid #e9ecef;">
            <div class="modal-header" style="background: white; border-bottom: 1px solid #e9ecef; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 600; color: #2c3e50;">
                    <i class="fas fa-edit"></i> Edit Urgency
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="opacity: 0.5;"></button>
            </div>
            <form id="editUrgencyForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUrgencyId">
                <div class="modal-body" style="padding: 20px;">
                    <!-- Urgency -->
                    <div class="mb-3">
                        <label for="editUrgency" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Urgency <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="editUrgency" name="urgency" 
                               required style="font-size: 13px; padding: 8px 12px;">
                        <div class="invalid-feedback" id="editUrgencyError" style="font-size: 12px; display: none; color: #dc3545;"></div>
                    </div>

                    <!-- Jam SLA -->
                    <div class="mb-3">
                        <label for="editJam" class="form-label" style="font-weight: 500; color: #555; font-size: 13px;">
                            Jam SLA <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="editJam" name="jam" 
                               min="1" max="9999" required style="font-size: 13px; padding: 8px 12px;">
                        <small class="text-muted" style="font-size: 12px;">Target SLA dalam jam</small>
                        <div class="invalid-feedback" id="editJamError" style="font-size: 12px; display: none; color: #dc3545;"></div>
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
    const baseUrl = "{{ route('urgency.index') }}";
    const csrfToken = "{{ csrf_token() }}";

    // Clear form error saat modal dibuka
    document.getElementById('addUrgencyModal').addEventListener('show.bs.modal', function() {
        clearFormErrors('add');
    });

    document.getElementById('editUrgencyModal').addEventListener('show.bs.modal', function() {
        clearFormErrors('edit');
    });

    // Handle form submit untuk TAMBAH
    document.getElementById('addUrgencyForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        
        try {
            const response = await fetch("{{ route('urgency.store') }}", {
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('addUrgencyModal'));
                modal.hide();

                // Reset form
                document.getElementById('addUrgencyForm').reset();

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

    // Function edit urgency
    function editUrgency(id) {
        fetch(`{{ url('urgency') }}/${id}`)
            .then(response => {
                if (!response.ok) throw new Error('Data tidak ditemukan');
                return response.json();
            })
            .then(data => {
                document.getElementById('editUrgencyId').value = data.id;
                document.getElementById('editUrgency').value = data.urgency;
                document.getElementById('editJam').value = data.jam;
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Gagal memuat data urgency');
            });
    }

    // Handle form submit untuk EDIT
    document.getElementById('editUrgencyForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const urgencyId = document.getElementById('editUrgencyId').value;
        const formData = new FormData(e.target);

        try {
            const response = await fetch(`{{ url('urgency') }}/${urgencyId}`, {
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('editUrgencyModal'));
                modal.hide();

                // Reset form
                document.getElementById('editUrgencyForm').reset();

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
        if (confirm(`Apakah Anda yakin ingin menghapus urgency "${name}"?`)) {
            deleteUrgency(id);
        }
    }

    // Function delete urgency
    function deleteUrgency(id) {
        fetch(`{{ url('urgency') }}/${id}`, {
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
        const formId = prefix === 'add' ? 'addUrgencyForm' : 'editUrgencyForm';
        document.querySelectorAll(`#${formId} .invalid-feedback`).forEach(el => {
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

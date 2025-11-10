@extends('layouts.main')

@section('title', 'Daftar Tiket - Helpdesk')

@section('page-title', 'Daftar Tiket')

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <!-- Table Tiket -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center" style="padding: 20px;">
            <h5 class="mb-0">Daftar Tiket</h5>
            @if($role === 'user')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTiketModal">
                    <i class="fas fa-plus"></i> Buat Tiket
                </button>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background-color: #f5f7fa;">
                            <th style="width: 50px; color: #666; font-weight: 600;">No</th>
                            <th style="color: #666; font-weight: 600;">Nomor Tiket</th>
                            <th style="color: #666; font-weight: 600;">Tanggal</th>
                            <th style="color: #666; font-weight: 600;">Judul</th>
                            <th style="color: #666; font-weight: 600;">Urgency</th>
                            <th style="color: #666; font-weight: 600;">Departemen</th>
                            <th style="color: #666; font-weight: 600;">Teknisi</th>
                            <th style="color: #666; font-weight: 600;">Status</th>
                            <th style="width: 100px; color: #666; font-weight: 600;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tikets as $tiket)
                            <tr>
                                <td>{{ ($tikets->currentPage() - 1) * $tikets->perPage() + $loop->index + 1 }}</td>
                                <td>
                                    <a href="javascript:void(0)" onclick="showDetail({{ $tiket->id }})" class="text-primary fw-bold">
                                        {{ $tiket->nomor }}
                                    </a>
                                </td>
                                <td>{{ $tiket->tanggal->format('d/m/Y H:i') }}</td>
                                <td>{{ Str::limit($tiket->judul, 40) }}</td>
                                <td>
                                    @if($tiket->urgency)
                                        <span class="badge bg-{{ $tiket->urgency->urgency === 'Urgent' ? 'danger' : ($tiket->urgency->urgency === 'High' ? 'warning' : ($tiket->urgency->urgency === 'Medium' ? 'info' : 'secondary')) }}">
                                            {{ $tiket->urgency->urgency }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>{{ $tiket->departemen->nama_departemen ?? '-' }}</td>
                                <td>{{ $tiket->teknisi->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $tiket->statusColor }}">
                                        {{ $tiket->statusLabel }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" onclick="showDetail({{ $tiket->id }})" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd;"></i><br>
                                    <span class="text-muted">Belum ada tiket</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($tikets->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $tikets->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- MODAL BUAT TIKET (USER) -->
@if($role === 'user')
<div class="modal fade" id="createTiketModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Buat Tiket Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tiket.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul" class="form-label" style="font-weight: 500; font-size: 13px;">
                            Judul <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                               id="judul" name="judul" value="{{ old('judul') }}" required 
                               style="font-size: 13px; padding: 8px 12px;">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label" style="font-weight: 500; font-size: 13px;">
                            Keterangan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="4" required
                                  style="font-size: 13px; padding: 8px 12px;">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="urgency_id" class="form-label" style="font-weight: 500; font-size: 13px;">
                            Urgency <span class="text-danger">*</span>
                        </label>
                        <select class="form-control @error('urgency_id') is-invalid @enderror" 
                                id="urgency_id" name="urgency_id" required
                                style="font-size: 13px; padding: 8px 12px;">
                            <option value="">-- Pilih Urgency --</option>
                            @foreach($urgencies as $urgency)
                                <option value="{{ $urgency->id }}" {{ old('urgency_id') == $urgency->id ? 'selected' : '' }}>
                                    {{ $urgency->urgency }} ({{ $urgency->jam }} jam)
                                </option>
                            @endforeach
                        </select>
                        @error('urgency_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="gambar" class="form-label" style="font-weight: 500; font-size: 13px;">
                            Lampiran Gambar
                        </label>
                        <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                               id="gambar" name="gambar" accept="image/*"
                               style="font-size: 12px; padding: 6px 10px;">
                        <small class="text-muted" style="font-size: 11px;">Maksimal 2MB, format: JPEG, PNG, JPG, GIF</small>
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-paper-plane"></i> Kirim Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- MODAL DETAIL TIKET - UKURAN COMPACT -->
<div class="modal fade" id="detailTiketModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 550px;">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 12px 20px; border: none;">
                <h5 class="modal-title text-white" style="font-weight: 600; font-size: 14px;">
                    <i class="fas fa-ticket-alt"></i> Detail Tiket
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailTiketContent" style="padding: 15px; background-color: #f8f9fa;">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa; padding: 10px 20px; border-top: 1px solid #dee2e6;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size: 11px; padding: 5px 12px;">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL REJECT TIKET (TEKNISI) -->
<div class="modal fade" id="rejectModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-times-circle"></i> Tolak Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejectCatatan" class="form-label" style="font-size: 12px; font-weight: 500;">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="rejectCatatan" name="catatan" rows="4" 
                                  placeholder="Jelaskan alasan Anda menolak tiket ini..." required
                                  style="font-size: 12px; padding: 8px 12px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-times"></i> Tolak Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const csrfToken = "{{ csrf_token() }}";
    const userRole = "{{ $role }}";
    const userId = {{ auth()->id() }};

    // Show Detail Tiket - LENGKAP DENGAN TEKNISI TERIMA/TOLAK
    function showDetail(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailTiketModal'));
        const content = document.getElementById('detailTiketContent');
        
        content.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>`;
        modal.show();
        
        fetch(`{{ url('tiket') }}/${id}`)
            .then(r => r.json())
            .then(data => {
                if (!data.success) throw new Error('Tiket tidak ditemukan');
                
                const tiket = data.tiket;
                const teknisis = data.teknisis;
                
                // Helper functions
                const getColor = s => ({'open': 'warning', 'pending': 'info', 'progress': 'primary', 'finish': 'success', 'close': 'secondary'}[s] || 'secondary');
                const getLabel = s => ({'open': 'Open', 'pending': 'Pending', 'progress': 'Progress', 'finish': 'Finish', 'close': 'Close'}[s] || s);
                const formatDate = d => {
                    const date = new Date(d);
                    return `${String(date.getDate()).padStart(2,'0')}/${String(date.getMonth()+1).padStart(2,'0')}/${date.getFullYear()} ${String(date.getHours()).padStart(2,'0')}:${String(date.getMinutes()).padStart(2,'0')}`;
                };

                const statusBadge = `<span class="badge bg-${getColor(tiket.status)}" style="font-size: 11px; padding: 4px 10px;">${getLabel(tiket.status)}</span>`;
                
                const urgencyColor = tiket.urgency ? 
                    ({'Urgent': 'danger', 'High': 'warning', 'Medium': 'info'}[tiket.urgency.urgency] || 'secondary') : 'secondary';
                const urgencyBadge = tiket.urgency ? 
                    `<span class="badge bg-${urgencyColor}" style="font-size: 11px; padding: 4px 10px;">${tiket.urgency.urgency}</span>` : 
                    '<span class="badge bg-secondary" style="font-size: 11px;">-</span>';
                
                const gambar = tiket.gambar ? 
                    `<a href="{{ asset('storage/') }}/${tiket.gambar}" target="_blank"><img src="{{ asset('storage/') }}/${tiket.gambar}" class="img-fluid rounded border" style="max-height: 180px; cursor: pointer;" alt="Lampiran"></a><br><small class="text-muted" style="font-size: 10px;">Klik untuk memperbesar</small>` : 
                    '<span class="text-muted" style="font-size: 11px;">Tidak ada lampiran</span>';

                // Alert penolakan untuk admin
                let rejectNoteSection = '';
                if ((userRole === 'admin' || userRole === 'administrator') && tiket.status === 'open' && tiket.catatan) {
                    rejectNoteSection = `<div class="alert alert-danger mb-2" style="padding: 10px; font-size: 11px;"><i class="fas fa-exclamation-circle"></i> <strong>⚠️ Alasan Penolakan:</strong><p style="margin-top: 5px; margin-bottom: 0;">${tiket.catatan}</p></div>`;
                }

                // Form Assign
                let assignSection = '';
                if ((userRole === 'admin' || userRole === 'administrator') && tiket.status === 'open') {
                    if (teknisis && teknisis.length > 0) {
                        let options = '<option value="">-- Pilih Teknisi --</option>';
                        teknisis.forEach(tek => {
                            const dept = tek.departemen ? ` - ${tek.departemen.nama_departemen}` : '';
                            options += `<option value="${tek.id}">${tek.nama}${dept}</option>`;
                        });
                        assignSection = `
                            <div class="card mb-2" style="border-left: 3px solid #0d6efd;">
                                <div class="card-body" style="padding: 12px;">
                                    <h6 class="mb-2" style="font-size: 12px; font-weight: 600; color: #333;">
                                        <i class="fas fa-user-plus"></i> Assign Teknisi
                                    </h6>
                                    <form onsubmit="submitAssignForm(event, ${tiket.id})">
                                        <div class="row">
                                            <div class="col-md-7 mb-2">
                                                <label class="form-label" style="font-size: 11px; font-weight: 500;">Pilih Teknisi <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="teknisi_id" required style="font-size: 11px; padding: 5px 8px;">${options}</select>
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label class="form-label" style="font-size: 11px; font-weight: 500;">Catatan</label>
                                                <input type="text" class="form-control form-control-sm" name="catatan" placeholder="Opsional" style="font-size: 11px; padding: 5px 8px;">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm" style="font-size: 11px; padding: 5px 12px;"><i class="fas fa-check"></i> Assign</button>
                                    </form>
                                </div>
                            </div>
                        `;
                    } else {
                        assignSection = `<div class="alert alert-warning mb-2" style="padding: 10px; font-size: 11px;"><i class="fas fa-exclamation-triangle"></i> Tidak ada teknisi tersedia untuk departemen ini.</div>`;
                    }
                }

                // Unassign
                let unassignSection = '';
                if ((userRole === 'admin' || userRole === 'administrator') && tiket.status === 'pending' && tiket.teknisi_id) {
                    unassignSection = `
                        <div class="card mb-2" style="border-left: 3px solid #ffc107;">
                            <div class="card-body" style="padding: 12px;">
                                <h6 class="mb-2" style="font-size: 12px; font-weight: 600; color: #333;"><i class="fas fa-user-times"></i> Batalkan Assignment</h6>
                                <p class="mb-2" style="font-size: 11px;">Teknisi: <strong>${tiket.teknisi.nama}</strong></p>
                                <button type="button" onclick="unassignTiket(${tiket.id})" class="btn btn-warning btn-sm" style="font-size: 11px; padding: 5px 12px;"><i class="fas fa-user-times"></i> Unassign</button>
                            </div>
                        </div>
                    `;
                }

                // Close
                let closeSection = '';
                if ((userRole === 'admin' || userRole === 'administrator') && tiket.status === 'finish') {
                    closeSection = `
                        <div class="card mb-2" style="border-left: 3px solid #6c757d;">
                            <div class="card-body" style="padding: 12px;">
                                <h6 class="mb-2" style="font-size: 12px; font-weight: 600; color: #333;"><i class="fas fa-check-circle"></i> Tutup Tiket</h6>
                                <button type="button" onclick="closeTiket(${tiket.id})" class="btn btn-secondary btn-sm" style="font-size: 11px; padding: 5px 12px;"><i class="fas fa-check-circle"></i> Close</button>
                            </div>
                        </div>
                    `;
                }

                // Teknisi Actions
                let teknisiSection = '';
                if (userRole === 'teknisi' && tiket.teknisi_id === userId) {
                    if (tiket.status === 'pending') {
                        teknisiSection = `
                            <div class="card mb-2" style="border-left: 3px solid #28a745;">
                                <div class="card-body" style="padding: 12px;">
                                    <h6 class="mb-2" style="font-size: 12px; font-weight: 600; color: #333;"><i class="fas fa-hand-paper"></i> Konfirmasi Tiket</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2"><button type="button" onclick="acceptTicket(${tiket.id})" class="btn btn-success btn-sm" style="font-size: 11px; padding: 5px 12px; width: 100%;"><i class="fas fa-check"></i> Terima</button></div>
                                        <div class="col-md-6 mb-2"><button type="button" onclick="openRejectModal(${tiket.id})" class="btn btn-danger btn-sm" style="font-size: 11px; padding: 5px 12px; width: 100%;"><i class="fas fa-times"></i> Tolak</button></div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else if (tiket.status === 'progress') {
                        teknisiSection = `
                            <div class="card mb-2" style="border-left: 3px solid #0d6efd;">
                                <div class="card-body" style="padding: 12px;">
                                    <h6 class="mb-2" style="font-size: 12px; font-weight: 600; color: #333;"><i class="fas fa-tasks"></i> Tandai Selesai</h6>
                                    <form onsubmit="submitFinishForm(event, ${tiket.id})">
                                        <div class="mb-2">
                                            <label class="form-label" style="font-size: 11px; font-weight: 500;">Catatan (Opsional)</label>
                                            <textarea class="form-control form-control-sm" name="solusi" rows="3" placeholder="Jelaskan solusi..." style="font-size: 11px; padding: 5px 8px;"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm" style="font-size: 11px; padding: 5px 12px; width: 100%;"><i class="fas fa-check-circle"></i> Selesai</button>
                                    </form>
                                </div>
                            </div>
                        `;
                    }
                }

                content.innerHTML = `
                    <div class="alert alert-primary mb-3" style="padding: 10px 15px; border-left: 3px solid #0d6efd;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0" style="font-size: 14px; font-weight: 600;"><i class="fas fa-ticket-alt"></i> ${tiket.nomor}</h6>
                            <div>${statusBadge}</div>
                        </div>
                    </div>

                    ${rejectNoteSection}

                    <div class="table-responsive mb-2">
                        <table class="table table-bordered table-sm mb-0" style="font-size: 11px;">
                            <tbody>
                                <tr><td style="width: 30%; background-color: #f8f9fa; font-weight: 600; padding: 6px 10px;"><i class="fas fa-calendar-alt text-primary" style="width: 16px; font-size: 10px;"></i> Tanggal</td><td style="padding: 6px 10px;">${formatDate(tiket.tanggal)}</td></tr>
                                <tr><td style="background-color: #f8f9fa; font-weight: 600; padding: 6px 10px;"><i class="fas fa-clock text-warning" style="width: 16px; font-size: 10px;"></i> Deadline</td><td style="padding: 6px 10px;">${tiket.tanggal_selesai ? formatDate(tiket.tanggal_selesai) : '-'}</td></tr>
                                <tr><td style="background-color: #f8f9fa; font-weight: 600; padding: 6px 10px;"><i class="fas fa-exclamation-triangle text-danger" style="width: 16px; font-size: 10px;"></i> Urgency</td><td style="padding: 6px 10px;">${urgencyBadge}</td></tr>
                                <tr><td style="background-color: #f8f9fa; font-weight: 600; padding: 6px 10px;"><i class="fas fa-building text-info" style="width: 16px; font-size: 10px;"></i> Departemen</td><td style="padding: 6px 10px;">${tiket.departemen ? tiket.departemen.nama_departemen : '-'}</td></tr>
                                <tr><td style="background-color: #f8f9fa; font-weight: 600; padding: 6px 10px;"><i class="fas fa-user text-secondary" style="width: 16px; font-size: 10px;"></i> Pembuat</td><td style="padding: 6px 10px;">${tiket.user ? tiket.user.nama : '-'}</td></tr>
                                <tr><td style="background-color: #f8f9fa; font-weight: 600; padding: 6px 10px;"><i class="fas fa-wrench text-success" style="width: 16px; font-size: 10px;"></i> Teknisi</td><td style="padding: 6px 10px;">${tiket.teknisi ? tiket.teknisi.nama : '<span class="text-muted">Belum ditugaskan</span>'}</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card mb-2" style="border-left: 3px solid #0d6efd;">
                        <div class="card-body" style="padding: 10px 12px;">
                            <h6 class="mb-1" style="font-size: 11px; font-weight: 600; color: #666;"><i class="fas fa-heading"></i> Judul</h6>
                            <p class="mb-0" style="font-size: 12px; font-weight: 500;">${tiket.judul}</p>
                        </div>
                    </div>

                    <div class="card mb-2" style="border-left: 3px solid #6c757d;">
                        <div class="card-body" style="padding: 10px 12px;">
                            <h6 class="mb-1" style="font-size: 11px; font-weight: 600; color: #666;"><i class="fas fa-align-left"></i> Keterangan</h6>
                            <p class="mb-0" style="font-size: 11px; white-space: pre-wrap; line-height: 1.5;">${tiket.keterangan}</p>
                        </div>
                    </div>

                    ${tiket.catatan && tiket.status !== 'open' ? `
                    <div class="card mb-2" style="border-left: 3px solid #ffc107;">
                        <div class="card-body" style="padding: 10px 12px;">
                            <h6 class="mb-1" style="font-size: 11px; font-weight: 600; color: #666;"><i class="fas fa-sticky-note"></i> Catatan Admin</h6>
                            <p class="mb-0" style="font-size: 11px; white-space: pre-wrap; line-height: 1.5;">${tiket.catatan}</p>
                        </div>
                    </div>
                    ` : ''}

                    ${tiket.solusi ? `
                    <div class="card mb-2" style="border-left: 3px solid #28a745;">
                        <div class="card-body" style="padding: 10px 12px;">
                            <h6 class="mb-1" style="font-size: 11px; font-weight: 600; color: #666;"><i class="fas fa-check-circle"></i> Solusi</h6>
                            <p class="mb-0" style="font-size: 11px; white-space: pre-wrap; line-height: 1.5;">${tiket.solusi}</p>
                        </div>
                    </div>
                    ` : ''}

                    <div class="card mb-2" style="border-left: 3px solid #17a2b8;">
                        <div class="card-body" style="padding: 10px 12px;">
                            <h6 class="mb-2" style="font-size: 11px; font-weight: 600; color: #666;"><i class="fas fa-paperclip"></i> Lampiran</h6>
                            <div class="text-center">${gambar}</div>
                        </div>
                    </div>

                    ${assignSection}
                    ${unassignSection}
                    ${closeSection}
                    ${teknisiSection}
                `;
            })
            .catch(err => {
                content.innerHTML = '<div class="alert alert-danger" style="font-size: 11px;"><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan</div>';
            });
    }

    // Functions
    function acceptTicket(id) {
        if (confirm('Terima tiket ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('tiket') }}/${id}/accept`;
            form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function openRejectModal(id) {
        document.getElementById('rejectForm').action = `{{ url('tiket') }}/${id}/reject`;
        document.getElementById('rejectCatatan').value = '';
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    }

    function submitFinishForm(e, id) {
        e.preventDefault();
        const form = new FormData(e.target);
        fetch(`{{ url('tiket') }}/${id}/finish`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: form
        }).then(r => r.ok ? location.reload() : alert('Error')).catch(() => alert('Error'));
    }

    function submitAssignForm(e, id) {
        e.preventDefault();
        const form = new FormData(e.target);
        fetch(`{{ url('tiket') }}/${id}/assign`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: form
        }).then(r => r.ok ? location.reload() : alert('Error')).catch(() => alert('Error'));
    }

    function unassignTiket(id) {
        if (confirm('Batalkan assignment?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('tiket') }}/${id}/unassign`;
            form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function closeTiket(id) {
        if (confirm('Close tiket ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('tiket') }}/${id}/status`;
            form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}"><input type="hidden" name="status" value="close">`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => bootstrap.Alert.getOrCreateInstance(a).close());
    }, 5000);

    @if($errors->any() && $role === 'user')
        new bootstrap.Modal(document.getElementById('createTiketModal')).show();
    @endif
</script>
@endsection

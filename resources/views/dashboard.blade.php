@extends('layouts.main')

@section('title', 'Dashboard - Helpdesk')

@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">Total Tiket</p>
                        <h3 class="mb-0" style="color: #667eea; font-weight: 700;">65</h3>
                        <small class="text-muted">Semua tiket masuk</small>
                    </div>
                    <i class="fas fa-ticket-alt" style="font-size: 30px; color: #667eea; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">Dalam Progress</p>
                        <h3 class="mb-0" style="color: #ffc107; font-weight: 700;">33</h3>
                        <small class="text-muted">Sedang ditangani</small>
                    </div>
                    <i class="fas fa-spinner" style="font-size: 30px; color: #ffc107; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">Selesai</p>
                        <h3 class="mb-0" style="color: #17a2b8; font-weight: 700;">24</h3>
                        <small class="text-muted">Sudah diselesaikan</small>
                    </div>
                    <i class="fas fa-check-circle" style="font-size: 30px; color: #17a2b8; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 12px; font-weight: 600; text-transform: uppercase;">Ditutup</p>
                        <h3 class="mb-0" style="color: #28a745; font-weight: 700;">8</h3>
                        <small class="text-muted">Sudah ditutup</small>
                    </div>
                    <i class="fas fa-lock" style="font-size: 30px; color: #28a745; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tiket Terbaru -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tiket Terbaru</h5>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#tiketTable">
            <i class="fas fa-chevron-down"></i> Lihat Semua
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr style="background-color: #f5f7fa;">
                        <th style="color: #666; font-weight: 600;">No. Tiket</th>
                        <th style="color: #666; font-weight: 600;">User</th>
                        <th style="color: #666; font-weight: 600;">Judul</th>
                        <th style="color: #666; font-weight: 600;">Status</th>
                        <th style="color: #666; font-weight: 600;">Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>#TKT001</strong></td>
                        <td>User Satu</td>
                        <td>Laptop tidak bisa dinyalakan</td>
                        <td><span class="badge bg-warning">Progress</span></td>
                        <td>2 jam yang lalu</td>
                    </tr>
                    <tr>
                        <td><strong>#TKT002</strong></td>
                        <td>User Dua</td>
                        <td>Reset password akun</td>
                        <td><span class="badge bg-success">Selesai</span></td>
                        <td>1 hari yang lalu</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Collapse Content -->
    <div class="collapse" id="tiketTable">
        <div class="card-body border-top">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background-color: #f5f7fa;">
                            <th style="color: #666; font-weight: 600;">No. Tiket</th>
                            <th style="color: #666; font-weight: 600;">User</th>
                            <th style="color: #666; font-weight: 600;">Judul</th>
                            <th style="color: #666; font-weight: 600;">Status</th>
                            <th style="color: #666; font-weight: 600;">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>#TKT003</strong></td>
                            <td>User Tiga</td>
                            <td>Error aplikasi di komputer</td>
                            <td><span class="badge bg-primary">Baru</span></td>
                            <td>30 menit yang lalu</td>
                        </tr>
                        <tr>
                            <td><strong>#TKT004</strong></td>
                            <td>User Empat</td>
                            <td>Printer tidak terdeteksi</td>
                            <td><span class="badge bg-warning">Progress</span></td>
                            <td>1 jam yang lalu</td>
                        </tr>
                        <tr>
                            <td><strong>#TKT005</strong></td>
                            <td>User Lima</td>
                            <td>Jaringan internet lambat</td>
                            <td><span class="badge bg-info">Tertunda</span></td>
                            <td>3 jam yang lalu</td>
                        </tr>
                        <tr>
                            <td><strong>#TKT006</strong></td>
                            <td>User Enam</td>
                            <td>Monitor rusak</td>
                            <td><span class="badge bg-danger">Ditolak</span></td>
                            <td>5 jam yang lalu</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animasi collapse button */
    .btn[data-bs-toggle="collapse"] {
        transition: all 0.3s ease;
    }

    .btn[data-bs-toggle="collapse"]:not(.collapsed) i {
        transform: rotate(180deg);
    }
</style>

@endsection

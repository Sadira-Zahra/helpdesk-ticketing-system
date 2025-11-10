@extends('layouts.main')

@section('title', 'Laporan Tiket - Helpdesk')

@section('page-title', 'Laporan Tiket')

@section('content')
<div class="container-fluid">
    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body" style="padding: 20px;">
            <h5 class="mb-3"><i class="fas fa-filter"></i> Filter Laporan</h5>
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label" style="font-size: 13px; font-weight: 500;">
                        Dari Tanggal <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ $startDate }}" style="font-size: 13px;">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label" style="font-size: 13px; font-weight: 500;">
                        Sampai Tanggal <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ $endDate }}" style="font-size: 13px;">
                </div>

                @if($role === 'administrator')
                <div class="col-md-3">
                    <label for="departemen_id" class="form-label" style="font-size: 13px; font-weight: 500;">
                        Departemen
                    </label>
                    <select class="form-control" id="departemen_id" name="departemen_id" style="font-size: 13px;">
                        <option value="">-- Semua Departemen --</option>
                        @foreach($departemens as $dept)
                            <option value="{{ $dept->id }}" {{ $departemenId == $dept->id ? 'selected' : '' }}>
                                {{ $dept->nama_departemen }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary" style="font-size: 13px; padding: 8px 16px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('tiket.laporan') }}" class="btn btn-secondary" style="font-size: 13px; padding: 8px 16px;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Laporan - Muncul hanya setelah filter diisi -->
    @if($showData)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom" style="padding: 20px;">
                <h5 class="mb-0">
                    <i class="fas fa-list"></i> Detail Laporan Tiket
                    @if($startDate && $endDate)
                        <small class="text-muted">({{ \Carbon\Carbon::createFromFormat('Y-m-d', $startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $endDate)->format('d/m/Y') }})</small>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background-color: #f5f7fa;">
                                <th style="width: 50px; color: #666; font-weight: 600;">No</th>
                                <th style="color: #666; font-weight: 600;">Nomor</th>
                                <th style="color: #666; font-weight: 600;">Tanggal</th>
                                <th style="color: #666; font-weight: 600;">Judul</th>
                                <th style="color: #666; font-weight: 600;">Departemen</th>
                                <th style="color: #666; font-weight: 600;">Teknisi</th>
                                <th style="color: #666; font-weight: 600;">Status</th>
                                <th style="color: #666; font-weight: 600;">Urgency</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tikets as $tiket)
                                <tr>
                                    <td>{{ ($tikets->currentPage() - 1) * $tikets->perPage() + $loop->index + 1 }}</td>
                                    <td>
                                        <span class="text-primary fw-bold" style="font-size: 13px;">{{ $tiket->nomor }}</span>
                                    </td>
                                    <td style="font-size: 13px;">{{ $tiket->tanggal->format('d/m/Y H:i') }}</td>
                                    <td style="font-size: 13px;">{{ Str::limit($tiket->judul, 35) }}</td>
                                    <td style="font-size: 13px;">{{ $tiket->departemen->nama_departemen ?? '-' }}</td>
                                    <td style="font-size: 13px;">{{ $tiket->teknisi->nama ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $tiket->statusColor }}" style="font-size: 11px; padding: 4px 8px;">
                                            {{ $tiket->statusLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($tiket->urgency)
                                            <span class="badge bg-{{ $tiket->urgency->urgency === 'Urgent' ? 'danger' : ($tiket->urgency->urgency === 'High' ? 'warning' : ($tiket->urgency->urgency === 'Medium' ? 'info' : 'secondary')) }}" style="font-size: 11px; padding: 4px 8px;">
                                                {{ $tiket->urgency->urgency }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 11px; padding: 4px 8px;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-inbox" style="font-size: 2rem; color: #ddd;"></i><br>
                                        <span class="text-muted" style="font-size: 13px;">Tidak ada data tiket untuk periode ini</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tikets->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $tikets->links() }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Empty State sebelum filter dipilih -->
        <div class="text-center py-5">
            <i class="fas fa-search" style="font-size: 3rem; color: #ddd; margin-bottom: 15px;"></i><br>
            <h5 class="text-muted" style="font-size: 16px; font-weight: 500;">
                Silakan pilih tanggal untuk menampilkan laporan
            </h5>
            <p class="text-muted" style="font-size: 13px; margin-top: 8px;">
                Isi field "Dari Tanggal" dan "Sampai Tanggal", kemudian klik tombol "Cari"
            </p>
        </div>
    @endif
</div>
@endsection

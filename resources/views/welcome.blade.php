@php
    $logo = url('templates/dist/img/' . rawurlencode('Logo Help Desk Putih.png'));
    
    // Data dummy untuk preview tampilan
    $dummyData = [
        (object)['devisi_nama' => 'IT Support', 'masuk' => 15, 'progress' => 8, 'selesai' => 5, 'close' => 2],
        (object)['devisi_nama' => 'Network', 'masuk' => 12, 'progress' => 6, 'selesai' => 4, 'close' => 2],
        (object)['devisi_nama' => 'Hardware', 'masuk' => 20, 'progress' => 10, 'selesai' => 8, 'close' => 2],
        (object)['devisi_nama' => 'Software', 'masuk' => 18, 'progress' => 9, 'selesai' => 7, 'close' => 2],
    ];
    
    $dummyTotal = [65, 33, 24, 8];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk MII</title>
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
            padding: 0;
            font-size: 14px;
        }

        /* Navbar */
        .navbar {
            background: rgba(8, 47, 73, 0.95);
            backdrop-filter: blur(10px);
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 1em;
            font-weight: 600;
            text-decoration: none;
            font-family: Arial, sans-serif;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .navbar-brand:hover {
            opacity: 0.8;
        }

        .navbar-brand .logo-icon {
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }

        .navbar-brand .logo-icon img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .login-btn {
            background: #3b82f6;
            border: none;
            color: white;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 0.85em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
            font-family: Arial, sans-serif;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
            background: #2563eb;
        }

        /* Hero Section */
        .hero-section {
            text-align: center;
            padding: 30px 20px 20px;
            color: white;
            font-family: Arial, sans-serif;
        }

        .hero-logo {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            padding: 8px;
        }

        .hero-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .hero-title {
            font-size: 1.8em;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            font-family: Arial, sans-serif;
        }

        .hero-subtitle {
            font-size: 0.95em;
            font-weight: 300;
            opacity: 0.9;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
        }

        .hero-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-user {
            background: #06b6d4;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 0.9em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 16px rgba(6, 182, 212, 0.3);
            text-decoration: none;
            font-family: Arial, sans-serif;
        }

        .btn-user:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4);
            background: #0891b2;
        }

        .btn-register {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 0.9em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
            text-decoration: none;
            font-family: Arial, sans-serif;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
            background: #059669;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 25px 15px;
            font-family: Arial, sans-serif;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-family: Arial, sans-serif;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .stat-card .stat-number {
            font-size: 2.2em;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1;
            font-family: Arial, sans-serif;
        }

        .stat-card .stat-label {
            font-size: 0.8em;
            color: #666;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: Arial, sans-serif;
        }

        .stat-card.blue .stat-number {
            color: #0c4a6e;
        }

        .stat-card.yellow .stat-number {
            color: #eab308;
        }

        .stat-card.green .stat-number {
            color: #10b981;
        }

        .stat-card.purple .stat-number {
            color: #7c3aed;
        }

        /* Table Section */
        .table-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-family: Arial, sans-serif;
        }

        .table-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
        }

        .table-header h2 {
            color: #0c4a6e;
            font-size: 1.3em;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: Arial, sans-serif;
        }

        .table-header .icon {
            font-size: 1.1em;
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            font-family: Arial, sans-serif;
        }

        thead {
            background: #0c4a6e;
        }

        thead th {
            padding: 12px 15px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 0.75em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            font-family: Arial, sans-serif;
        }

        thead th:first-child {
            border-radius: 10px 0 0 0;
            text-align: center;
            width: 60px;
        }

        thead th:last-child {
            border-radius: 0 10px 0 0;
        }

        tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.2s ease;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        tbody tr:last-child td:first-child {
            border-radius: 0 0 0 10px;
        }

        tbody tr:last-child td:last-child {
            border-radius: 0 0 10px 0;
        }

        tbody td {
            padding: 12px 15px;
            color: #4b5563;
            font-size: 0.85em;
            font-family: Arial, sans-serif;
        }

        tbody td:first-child {
            text-align: center;
            font-weight: 700;
            color: #0c4a6e;
            font-size: 0.9em;
        }

        tbody td:nth-child(2) {
            font-weight: 600;
            color: #1f2937;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75em;
            font-weight: 600;
            font-family: Arial, sans-serif;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-yellow {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-green {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty-row {
            text-align: center;
            padding: 30px 15px;
            color: #9ca3af;
            font-family: Arial, sans-serif;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            body {
                font-size: 13px;
            }

            .navbar {
                padding: 8px 15px;
            }

            .navbar-brand {
                font-size: 0.9em;
            }

            .hero-title {
                font-size: 1.5em;
            }

            .hero-subtitle {
                font-size: 0.85em;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-user,
            .btn-register {
                width: 100%;
                max-width: 250px;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-card .stat-number {
                font-size: 1.8em;
            }

            .table-section {
                padding: 15px;
            }

            thead th,
            tbody td {
                padding: 10px 8px;
                font-size: 0.75em;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.3em;
            }

            .navbar-brand span {
                display: none;
            }

            thead th,
            tbody td {
                font-size: 0.7em;
                padding: 8px 6px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">
            <div class="logo-icon">
                <img src="{{ $logo }}" alt="Logo">
            </div>
            <span>Helpdesk</span>
        </a>
        <a href="{{ route('login_petugas') }}" class="login-btn">
            <span>🔐</span>
            <span>Login Petugas</span>
        </a>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-logo">
            <img src="{{ $logo }}" alt="Logo Help Desk">
        </div>
        <h1 class="hero-title">Selamat Datang di Layanan Helpdesk</h1>
        <p class="hero-subtitle">Sistem Manajemen Tiket Terintegrasi</p>
        <div class="hero-buttons">
            <a href="{{ route('login_user') }}" class="btn-user">
                <span>👤</span>
                <span>Login User</span>
            </a>
            <a href="{{ route('register_user') }}" class="btn-register">
                <span>📝</span>
                <span>Daftar User</span>
            </a>
        </div>
    </section>

    <!-- Main Container -->
    <div class="container">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-number">{{ $dummyTotal[0] }}</div>
                <div class="stat-label">
                    <span>📥</span>
                    <span>Total Tiket Masuk</span>
                </div>
            </div>
            <div class="stat-card yellow">
                <div class="stat-number">{{ $dummyTotal[1] }}</div>
                <div class="stat-label">
                    <span>⏳</span>
                    <span>Dalam Progress</span>
                </div>
            </div>
            <div class="stat-card green">
                <div class="stat-number">{{ $dummyTotal[2] }}</div>
                <div class="stat-label">
                    <span>✅</span>
                    <span>Selesai</span>
                </div>
            </div>
            <div class="stat-card purple">
                <div class="stat-number">{{ $dummyTotal[3] }}</div>
                <div class="stat-label">
                    <span>🔒</span>
                    <span>Closed</span>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-section">
            <div class="table-header">
                <h2>
                    <span class="icon">📊</span>
                    <span>Informasi Tiket</span>
                </h2>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>DEVISI</th>
                            <th>JUMLAH TIKET MASUK</th>
                            <th>JUMLAH TIKET PROGRESS</th>
                            <th>JUMLAH TIKET SELESAI</th>
                            <th>JUMLAH TIKET CLOSE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dummyData as $i => $r)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $r->devisi_nama }}</td>
                            <td><span class="badge badge-blue">{{ $r->masuk }}</span></td>
                            <td><span class="badge badge-yellow">{{ $r->progress }}</span></td>
                            <td><span class="badge badge-green">{{ $r->selesai }}</span></td>
                            <td><span class="badge badge-red">{{ $r->close }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-row">Belum ada data tiket</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

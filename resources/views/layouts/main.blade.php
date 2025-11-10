<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Helpdesk Ticketing System')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: white;
            color: #333;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            padding-top: 0;
            z-index: 1000;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
        }

        /* Sidebar Collapsed */
        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .sidebar-header h5 {
            font-size: 0;
        }

        .sidebar.collapsed .sidebar-header i {
            font-size: 24px;
        }

        .sidebar.collapsed .sidebar-menu > li > a span,
        .sidebar.collapsed .sidebar-menu > li > .menu-toggle span,
        .sidebar.collapsed .submenu li a span {
            display: none;
        }

        .sidebar.collapsed .sidebar-menu > li > a,
        .sidebar.collapsed .sidebar-menu > li > .menu-toggle {
            padding: 12px;
            justify-content: center;
        }

        .sidebar.collapsed .menu-toggle .arrow {
            display: none;
        }

        .sidebar.collapsed .submenu {
            position: absolute;
            left: 80px;
            top: 0;
            width: 200px;
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            display: none;
        }

        .sidebar.collapsed .submenu.show {
            display: block;
        }

        .sidebar.collapsed .sidebar-footer {
            position: static;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        /* ===== NAVBAR ===== */
        .navbar-top {
            background: white;
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 0;
            right: 0;
            left: 260px;
            height: 60px;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: left 0.3s ease;
            border-bottom: 1px solid #e9ecef;
        }

        .navbar-top.collapsed {
            left: 80px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            margin-left: 260px;
            margin-top: 60px;
            min-height: calc(100vh - 60px);
            padding: 30px;
            transition: margin-left 0.3s ease;
            background-color: #f5f7fa;
        }

        .main-wrapper.collapsed {
            margin-left: 80px;
        }

        .content-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        /* Overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: 250px;
                transform: translateX(-100%);
            }

            .navbar-top {
                left: 0;
            }

            .navbar-top.collapsed {
                left: 0;
            }

            .main-wrapper {
                margin-left: 0;
                padding: 15px;
            }

            .main-wrapper.collapsed {
                margin-left: 0;
            }

            .sidebar.collapsed .submenu {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .main-wrapper {
                padding: 10px;
            }

            .sidebar {
                width: 240px;
            }
        }
    </style>

    @yield('styles')
</head>
<body>

    <!-- SIDEBAR OVERLAY (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SIDEBAR -->
    @include('layouts.components.sidebar')

    <!-- NAVBAR TOP -->
    @include('layouts.components.navbar')

    <!-- MAIN CONTENT -->
    <div class="main-wrapper" id="mainWrapper">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <strong>Error!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const navbar = document.querySelector('.navbar-top');
            const mainWrapper = document.getElementById('mainWrapper');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Check localStorage for sidebar state
            const sidebarState = localStorage.getItem('sidebarState') || 'expanded';
            if (sidebarState === 'collapsed') {
                sidebar.classList.add('collapsed');
                navbar.classList.add('collapsed');
                mainWrapper.classList.add('collapsed');
                if (toggleBtn) {
                    toggleBtn.innerHTML = '<i class="fas fa-angles-right"></i>';
                }
            }

            // Toggle Sidebar Expand/Collapse (Desktop)
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('collapsed');
                    navbar.classList.toggle('collapsed');
                    mainWrapper.classList.toggle('collapsed');

                    // Update icon
                    if (sidebar.classList.contains('collapsed')) {
                        toggleBtn.innerHTML = '<i class="fas fa-angles-right"></i>';
                        localStorage.setItem('sidebarState', 'collapsed');
                    } else {
                        toggleBtn.innerHTML = '<i class="fas fa-angles-left"></i>';
                        localStorage.setItem('sidebarState', 'expanded');
                    }
                });
            }

            // Toggle Hamburger (Mobile)
            if (hamburgerBtn) {
                hamburgerBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (window.innerWidth <= 768) {
                        sidebar.classList.add('show');
                        sidebarOverlay.classList.add('show');
                    }
                });
            }

            // Close sidebar saat klik overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            // Close sidebar saat item diklik
            document.querySelectorAll('.sidebar a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    }
                });
            });

            // User menu dropdown
            const userProfile = document.querySelector('.user-profile');
            const userDropdown = document.getElementById('userDropdown');

            if (userProfile) {
                userProfile.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (userDropdown) {
                        userDropdown.classList.toggle('show');
                    }
                });
            }

            document.addEventListener('click', function(event) {
                if (userDropdown && !event.target.closest('.user-profile')) {
                    userDropdown.classList.remove('show');
                }
            });

            // Handle resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });
    </script>

    @yield('scripts')

</body>
</html>

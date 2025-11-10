@php
    $user = auth()->user();
    $initial = strtoupper(substr($user->nama ?? 'U', 0, 1));
    $avatarFallback = 'https://via.placeholder.com/36/667eea/ffffff?text=' . urlencode($initial);
    $avatarSrc = $user->photo ? asset('storage/' . $user->photo) : $avatarFallback;
@endphp

<style>
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

    .navbar-left {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 1;
    }

    .hamburger-btn,
    .sidebar-toggle-btn {
        background: none;
        border: none;
        color: #2c3e50;
        font-size: 20px;
        cursor: pointer;
        padding: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 6px;
    }

    .hamburger-btn:hover,
    .sidebar-toggle-btn:hover {
        background-color: #f0f0f0;
        color: #667eea;
    }

    .navbar-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        flex: 1;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 6px;
        transition: background 0.3s ease;
        position: relative;
    }

    .user-profile:hover {
        background-color: #f0f0f0;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #667eea;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        font-size: 13px;
    }

    .user-info strong {
        color: #2c3e50;
        font-size: 14px;
    }

    .user-info small {
        color: #999;
    }

    #userDropdown {
        position: absolute;
        top: 50px;
        right: 0;
        background: white;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        min-width: 200px;
        display: none;
        z-index: 1001;
    }

    #userDropdown.show {
        display: block;
        animation: slideDown 0.2s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #userDropdown a,
    #userDropdown button {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        transition: background 0.3s ease;
    }

    #userDropdown a:hover,
    #userDropdown button:hover {
        background-color: #f5f5f5;
    }

    #userDropdown a:last-child,
    #userDropdown button:last-child {
        border-bottom: none;
    }

    #userDropdown button {
        color: #d32f2f;
    }

    @media (max-width: 768px) {
        .navbar-top {
            left: 0;
            padding: 0 15px;
        }

        .sidebar-toggle-btn {
            display: none;
        }

        .navbar-title {
            font-size: 16px;
        }

        .user-info {
            display: none;
        }

        .navbar-right {
            gap: 10px;
        }

        #userDropdown {
            right: -15px;
        }

        .hamburger-btn {
            width: 36px;
            height: 36px;
        }
    }

    @media (min-width: 769px) {
        .hamburger-btn {
            display: none;
        }
    }
</style>

<div class="navbar-top" id="navbarTop">
    <div class="navbar-left">
        <!-- Toggle Sidebar (Desktop) -->
        <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Hamburger Menu (Mobile) -->
        <button type="button" class="hamburger-btn" id="hamburgerBtn" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <h1 class="navbar-title">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="navbar-right">
        <div class="user-profile" id="userMenuBtn">
            <img src="{{ $avatarSrc }}" alt="{{ $user->nama }}" class="user-avatar">
            <div class="user-info">
                <strong>{{ $user->nama }}</strong>
                <small>{{ ucfirst($user->role) }}</small>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');

        // Toggle User Menu
        if (userMenuBtn) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (userDropdown) {
                    userDropdown.classList.toggle('show');
                }
            });
        }

        // Close user menu saat klik di luar
        document.addEventListener('click', function(event) {
            if (userDropdown && !event.target.closest('.user-profile')) {
                userDropdown.classList.remove('show');
            }
        });

        // Hamburger menu untuk mobile
        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
        }
    });
</script>

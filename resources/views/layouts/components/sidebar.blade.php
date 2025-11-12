@php
    $auth = auth()->user();
    $role = $auth->role ?? 'user';
    
    $item = function($title, $path = '#', $icon = 'fas fa-circle', $extra = []) {
        return (object) array_merge([
            'title' => $title,
            'path' => $path,
            'icon' => $icon,
        ], $extra);
    };
    
    $linkFor = function($path) {
        if ($path === '#' || empty($path)) return '#';
        if (strpos($path, '.') !== false) {
            try {
                return route($path);
            } catch (\Throwable $e) {
                return url($path);
            }
        }
        if (preg_match('#^https?://#i', $path) || strpos($path, '/') === 0) return $path;
        return url($path);
    };
    
    $isActive = function($path) use ($linkFor) {
        if ($path === '#') return false;
        $href = $linkFor($path);
        $current = url(request()->path());
        return rtrim($href, '/') === rtrim($current, '/');
    };

    // Menu berdasarkan role
    if ($role === 'administrator') {
        $menus = [
            $item('Dashboard', 'dashboard', 'fas fa-home'),
            (object)[
                "title" => "Master User",
                "icon" => "fas fa-users",
                "has_submenu" => true,
                "submenu" => [
                    $item("Administrator", 'administrator.index', "fas fa-shield-alt"),
                    $item("Admin", 'admin.index', "fas fa-user-tie"),
                    $item("Teknisi", 'teknisi.index', "fas fa-wrench"),
                    $item("User", 'user.index', "fas fa-user"),
                ]
            ],
            (object)[
                "title" => "Master Data",
                "icon" => "fas fa-cog",
                "has_submenu" => true,
                "submenu" => [
                    $item("Departemen", 'departemen.index', "fas fa-sitemap"),
                    $item("Urgency", 'urgency.index', "fas fa-exclamation-circle"),
                ]
            ],
            $item('Tiket', 'tiket.index', 'fas fa-ticket-alt'),
            (object)[
                "title" => "Laporan",
                "icon" => "fas fa-chart-bar",
                "has_submenu" => true,
                "submenu" => [
                    $item("Laporan Tiket", "tiket.laporan", "fas fa-list"),
                ]
            ],
            $item('Profil', 'ganti_profil.index', 'fas fa-user-cog'),
        ];
    } elseif ($role === 'admin') {
        $menus = [
            $item('Dashboard', 'dashboard', 'fas fa-home'),
            $item('Tiket', 'tiket.index', 'fas fa-ticket-alt'),
            (object)[
                "title" => "Laporan",
                "icon" => "fas fa-chart-bar",
                "has_submenu" => true,
                "submenu" => [
                    $item("Laporan Tiket", "tiket.laporan", "fas fa-list"),
                ]
            ],
            $item('Profil', 'ganti_profil.index', 'fas fa-user-cog'),
        ];
    } elseif ($role === 'teknisi') {
        $menus = [
            $item('Dashboard', 'dashboard', 'fas fa-home'),
            $item('Tiket Saya', 'tiket.index', 'fas fa-ticket-alt'),
            $item('Laporan', 'tiket.laporan', 'fas fa-chart-bar'),
            $item('Profil', 'ganti_profil.index', 'fas fa-user-cog'),
        ];
    } else {
        // Role user
        $menus = [
            $item('Dashboard', 'dashboard', 'fas fa-home'),
            $item('Tiket Saya', 'tiket.index', 'fas fa-ticket-alt'),
            $item('Laporan', 'tiket.laporan', 'fas fa-chart-bar'),
            $item('Profil', 'ganti_profil.index', 'fas fa-user-cog'),
        ];
    }
@endphp

<style>
    .sidebar {
        background: white;
        border-right: 1px solid #e9ecef;
    }

    .sidebar-header {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        text-align: center;
        border-bottom: none;
    }

    .sidebar-header h5 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .sidebar-menu {
        list-style: none;
        padding: 15px 0;
        margin: 0;
    }

    .sidebar-menu > li {
        margin: 0;
    }

    .sidebar-menu > li > a,
    .sidebar-menu > li > .menu-toggle {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        color: #555;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
        border-left: 3px solid transparent;
    }

    .sidebar-menu > li > a:hover,
    .sidebar-menu > li > .menu-toggle:hover {
        background-color: #f5f5f5;
        color: #333;
        border-left-color: #667eea;
    }

    .sidebar-menu > li > a.active,
    .sidebar-menu > li > .menu-toggle.active {
        background-color: #f0f2f7;
        color: #667eea;
        border-left-color: #667eea;
        font-weight: 600;
    }

    .sidebar-menu i {
        width: 20px;
        text-align: center;
        font-size: 16px;
    }

    .submenu {
        list-style: none;
        padding: 0;
        background-color: #fafbfc;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        margin: 0;
    }

    .submenu.show {
        max-height: 500px;
    }

    .submenu li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px 10px 52px;
        color: #666;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 13px;
        border-left: 3px solid transparent;
    }

    .submenu li a:hover {
        background-color: #eff1f6;
        color: #333;
        border-left-color: #667eea;
    }

    .submenu li a.active {
        background-color: #e8ecf6;
        color: #667eea;
        border-left-color: #667eea;
        font-weight: 600;
    }

    .menu-toggle {
        cursor: pointer;
        background: none;
        border: none;
        color: #555;
        width: 100%;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .menu-toggle:focus {
        outline: none;
    }

    .menu-toggle .arrow {
        font-size: 12px;
        transition: transform 0.3s ease;
        margin-left: auto;
    }

    .menu-toggle.active .arrow {
        transform: rotate(180deg);
    }

    .sidebar-footer {
        padding: 15px 20px;
        border-top: 1px solid #e9ecef;
        margin-top: auto;
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 15px;
        background-color: #ffe5e5;
        color: #d32f2f;
        text-decoration: none;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
    }

    .logout-btn:hover {
        background-color: #ffcccc;
        color: #b71c1c;
    }
</style>

<div class="sidebar">
    <!-- Header -->
    <div class="sidebar-header">
        <h5>
            <i class="fas fa-headset"></i>
            Helpdesk
        </h5>
    </div>

    <!-- Menu Items -->
    <ul class="sidebar-menu">
        @foreach ($menus as $menu)
            @if (isset($menu->has_submenu) && $menu->has_submenu)
                <li>
                    <button type="button" class="menu-toggle" onclick="toggleSubmenu(this)">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="{{ $menu->icon }}"></i>
                            <span>{{ $menu->title }}</span>
                        </div>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <ul class="submenu">
                        @foreach ($menu->submenu as $submenu)
                            <li>
                                <a href="{{ $linkFor($submenu->path) }}" 
                                   class="@if($isActive($submenu->path)) active @endif">
                                    <i class="{{ $submenu->icon }}"></i>
                                    <span>{{ $submenu->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li>
                    <a href="{{ $linkFor($menu->path) }}"
                       class="@if($isActive($menu->path)) active @endif">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->title }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>

    <!-- Logout Button -->
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" style="margin: 10;">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

<script>
    function toggleSubmenu(button) {
        const submenu = button.nextElementSibling;
        submenu.classList.toggle('show');
        button.classList.toggle('active');
    }

    // Aktifkan submenu parent jika ada submenu yang aktif
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.submenu li a.active').forEach(activeLink => {
            const submenu = activeLink.closest('.submenu');
            if (submenu) {
                const toggle = submenu.previousElementSibling;
                if (toggle && toggle.classList.contains('menu-toggle')) {
                    submenu.classList.add('show');
                    toggle.classList.add('active');
                }
            }
        });
    });
</script>

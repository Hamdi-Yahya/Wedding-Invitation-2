<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Wedding Invitation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>

<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <span class="logo-icon">💛</span>
                <div>
                    <h2>WEDDING INVITATION</h2>
                    <span class="admin-badge">- ADMIN -</span>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                <span>Dashboard</span>
            </a>

            <div
                class="nav-group {{ request()->routeIs('dashboard.guests') || request()->routeIs('dashboard.scanner') ? 'open' : '' }}">
                <button class="nav-group-toggle" onclick="toggleNavGroup(this)">
                    <span class="nav-icon">👥</span>
                    <span>Tamu</span>
                    <span class="nav-arrow">▸</span>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('dashboard.guests') }}"
                        class="nav-item nav-sub {{ request()->routeIs('dashboard.guests') ? 'active' : '' }}">
                        <span>Guest List</span>
                    </a>
                    <a href="{{ route('dashboard.scanner') }}"
                        class="nav-item nav-sub {{ request()->routeIs('dashboard.scanner') ? 'active' : '' }}">
                        <span>Scanner</span>
                    </a>
                </div>
            </div>

            <div
                class="nav-group {{ request()->routeIs('dashboard.event-settings') || request()->routeIs('dashboard.wishes') || request()->routeIs('dashboard.gallery') ? 'open' : '' }}">
                <button class="nav-group-toggle" onclick="toggleNavGroup(this)">
                    <span class="nav-icon">📋</span>
                    <span>Konten</span>
                    <span class="nav-arrow">▸</span>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('dashboard.event-settings') }}"
                        class="nav-item nav-sub {{ request()->routeIs('dashboard.event-settings') ? 'active' : '' }}">
                        <span>Edit Details</span>
                    </a>
                    <a href="{{ route('dashboard.wishes') }}"
                        class="nav-item nav-sub {{ request()->routeIs('dashboard.wishes') ? 'active' : '' }}">
                        <span>Ucapan & RSVP</span>
                    </a>
                    <a href="{{ route('dashboard.gallery') }}"
                        class="nav-item nav-sub {{ request()->routeIs('dashboard.gallery') ? 'active' : '' }}">
                        <span>Galeri</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('dashboard.theme-settings') }}"
                class="nav-item {{ request()->routeIs('dashboard.theme-settings') ? 'active' : '' }}">
                <span class="nav-icon">🎨</span>
                <span>Design & Theme</span>
            </a>

            <a href="{{ route('dashboard.export') }}"
                class="nav-item {{ request()->routeIs('dashboard.export') ? 'active' : '' }}">
                <span class="nav-icon">📤</span>
                <span>Export</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item logout-btn">
                    <span class="nav-icon">🚪</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <main class="main-content">
        <div class="content-header">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <div class="header-actions">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline">
                    🔗 View Live Site
                </a>
            </div>
        </div>

        <div class="content-body">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        function toggleNavGroup(btn) {
            const group = btn.closest('.nav-group');
            group.classList.toggle('open');
        }
    </script>
    @stack('scripts')
</body>

</html>
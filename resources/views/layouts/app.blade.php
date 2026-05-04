<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LinkLearn')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #0f172a; 
            --brand-light: #1e293b;
            --brand-soft: #f1f5f9;
            --accent: #f59e0b; 
            --accent-hover: #d97706;
            --bg: #ffffff;
            --surface: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --danger: #ef4444;
            --border: #e2e8f0;
            --radius-lg: 1rem;
            --radius-md: 0.75rem;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg); color: var(--text-main); min-height: 100vh; display: flex; }

        /* Sidebar */
        aside {
            width: var(--sidebar-width);
            background: var(--brand);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            border-right: 1px solid var(--brand-light);
            z-index: 100;
        }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid var(--brand-light); }
        .sidebar-logo { font-size: 1.25rem; font-weight: 800; color: white; text-decoration: none; display: flex; align-items: center; gap: 0.75rem; }
        .sidebar-logo div { width: 1.5rem; height: 1.5rem; background: var(--accent); border-radius: 0.4rem; }
        .sidebar-nav { flex-grow: 1; padding: 1.5rem 0.75rem; display: flex; flex-direction: column; gap: 0.5rem; }
        .nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1rem; text-decoration: none; color: #94a3b8; font-weight: 600; font-size: 0.9rem; border-radius: var(--radius-md); transition: 0.2s; }
        .nav-link:hover { background: var(--brand-light); color: white; }
        .nav-link.active { background: var(--accent); color: var(--brand); }

        /* Content Wrapper */
        .wrapper { flex-grow: 1; display: flex; flex-direction: column; width: 100%; }
        .with-sidebar { margin-left: var(--sidebar-width); }

        header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 90;
            width: 100%;
        }

        /* Perfectly Spread the Header Content when Public */
        .header-public { justify-content: space-between; }
        .header-auth { justify-content: flex-end; }

        main { padding: 2rem; flex-grow: 1; display: flex; flex-direction: column; align-items: center; width: 100%; }

        /* Dropdown */
        .user-dropdown { position: relative; }
        .dropdown-trigger { display: flex; align-items: center; gap: 0.75rem; background: var(--surface); border: 1px solid var(--border); padding: 0.4rem 0.4rem 0.4rem 1rem; border-radius: 9999px; cursor: pointer; }
        .avatar { width: 2rem; height: 2rem; border-radius: 50%; border: 2px solid var(--accent); }
        .dropdown-menu { position: absolute; top: calc(100% + 0.5rem); right: 0; background: white; border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); width: 240px; padding: 0.5rem; display: none; z-index: 50; }
        .dropdown-menu.show { display: block; }
        .dropdown-item { width: 100%; text-align: left; padding: 0.75rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; font-weight: 600; color: var(--text-main); cursor: pointer; border: none; background: none; display: flex; align-items: center; gap: 0.75rem; }
        .dropdown-item:hover { background: var(--brand-soft); }

        /* Utility */
        .btn { padding: 0.875rem 1.75rem; border-radius: var(--radius-md); font-weight: 700; font-size: 0.9rem; text-decoration: none; cursor: pointer; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem; }
        .btn-primary { background: var(--brand); color: #fff; }
        .btn-accent { background: var(--accent); color: var(--brand); }
        .btn-outline { background: #fff; border: 2px solid var(--brand); color: var(--brand); }
    </style>
</head>
<body>
    @auth
    @php
        $currentOrg     = request()->get('current_org');
        $isTenantContext = tenancy()->initialized ?? false;
        $user           = auth()->user();

        // Build the central domain base URL so we can link back from any subdomain.
        $centralDomains = config('tenancy.central_domains', ['localhost']);
        $centralDomain  = $centralDomains[0];
        $port           = request()->getPort();
        $portStr        = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
        $centralBase    = request()->getScheme() . '://' . $centralDomain . $portStr;

        // Teachers and students always use the central domain for navigation.
        // Admins/org_admins use the tenant subdomain if they are already there.
        $isStudentOrTeacher = $user->isStudent() || $user->isTeacher();

        // Dashboard link
        if ($isStudentOrTeacher) {
            $dashboardLink = $isTenantContext ? $centralBase . '/dashboard' : route('dashboard');
        } else {
            $dashboardLink = $isTenantContext ? '/dashboard' : route('dashboard');
        }

        // Classrooms link — teachers & students always go to the central aggregate list
        if ($isStudentOrTeacher) {
            $classroomsLink = $isTenantContext ? $centralBase . '/rooms' : route('rooms.index');
        } else {
            $orgSlug = $currentOrg?->slug ?? $user->organization?->slug;
            $classroomsLink = $isTenantContext ? '/rooms'
                : ($orgSlug ? route('org.rooms.index', ['tenant' => $orgSlug]) : route('rooms.index'));
        }

        // Archived link
        if ($isStudentOrTeacher) {
            $archivedRoute = $isTenantContext ? $centralBase . '/archived' : route('rooms.archived');
        } else {
            $archivedRoute = $isTenantContext ? '/archived' : route('rooms.archived');
        }
    @endphp

    <aside>
        <div class="sidebar-header">
            <a href="{{ $centralBase }}" class="sidebar-logo"><div></div> LinkLearn</a>
        </div>
        <nav class="sidebar-nav">
            @if($user->isSuperAdmin())
                <a href="{{ $isTenantContext ? $centralBase . '/dashboard' : route('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ $isTenantContext ? $centralBase . '/admin/organizations' : route('admin.organizations') }}" class="nav-link {{ request()->is('admin/organizations*') ? 'active' : '' }}">Tenants</a>
                <a href="{{ $isTenantContext ? $centralBase . '/admin/monitoring' : route('admin.monitoring') }}" class="nav-link {{ request()->is('admin/monitoring*') ? 'active' : '' }}">Global Monitoring</a>
            @elseif($user->isTeacher())
                <a href="{{ $dashboardLink }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ $classroomsLink }}" class="nav-link {{ request()->is('rooms') || request()->is('org/*/rooms') ? 'active' : '' }}">Classrooms</a>
                <a href="{{ $archivedRoute }}" class="nav-link {{ request()->is('archived') ? 'active' : '' }}">Archived</a>
                @if($currentOrg && $currentOrg->status !== 'active')
                    <a href="{{ $isTenantContext ? $centralBase . '/org/' . $currentOrg->slug . '/subscription/payment' : route('org.subscription.payment', $currentOrg->slug) }}" class="nav-link {{ request()->is('org/*/subscription/payment*') ? 'active' : '' }}">Pay Subscription</a>
                @endif
            @else
                {{-- Student and other roles --}}
                <a href="{{ $dashboardLink }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ $classroomsLink }}" class="nav-link {{ request()->is('rooms*') || request()->is('org/*/rooms*') ? 'active' : '' }}">Classrooms</a>
                @if($currentOrg && $currentOrg->status !== 'active')
                    <a href="{{ $isTenantContext ? $centralBase . '/org/' . $currentOrg->slug . '/subscription/payment' : route('org.subscription.payment', $currentOrg->slug) }}" class="nav-link {{ request()->is('org/*/subscription/payment*') ? 'active' : '' }}">Pay Subscription</a>
                @endif
            @endif
        </nav>
    </aside>
    @endauth

    <div class="wrapper {{ auth()->check() ? 'with-sidebar' : '' }}">
        <header class="{{ auth()->check() ? 'header-auth' : 'header-public' }}">
            @if(auth()->check())
                <div class="user-dropdown">
                    <button class="dropdown-trigger" id="profileBtn">
                        <span style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">{{ auth()->user()->name }}</span>
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=f59e0b&color=0f172a' }}" alt="Avatar" class="avatar">
                    </button>
                    <div class="dropdown-menu" id="profileMenu">
                        <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--border); margin-bottom: 0.25rem;">
                            <strong style="font-size: 0.75rem; color: var(--text-muted); display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500;">
                                {{ auth()->user()->email }}
                            </strong>
                        </div>
                        <form action="{{ (function_exists('tenant') && tenant()) ? route('org.logout') : route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item" style="color: var(--danger);">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Truly Centered Navbar Elements for Public --}}
                <a href="/" class="sidebar-logo" style="color: var(--brand);"><div></div> LinkLearn</a>
                <button type="button" onclick="window.location.href='{{ route('login') }}'" class="btn btn-primary" style="padding: 0.6rem 1.5rem; font-size: 0.8rem; cursor: pointer;">Login</button>
            @endif
        </header>

        <main>
            @yield('content')
        </main>
    </div>

    <script>
        const profileBtn = document.getElementById('profileBtn');
        const profileMenu = document.getElementById('profileMenu');
        if (profileBtn) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('show');
            });
            document.addEventListener('click', () => profileMenu && profileMenu.classList.remove('show'));
        }
    </script>
</body>
</html>

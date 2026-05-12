<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LinkLearn')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Footer */
        .app-footer { margin-top: auto; padding: 1.5rem 2rem; text-align: center; font-size: 0.875rem; color: var(--text-muted); border-top: 1px solid var(--border); }

        /* Update Modal */
        .update-modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 1000; display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .update-modal-overlay.show { opacity: 1; pointer-events: auto; }
        .update-modal { background: #fff; width: 90%; max-width: 380px; border-radius: 1.5rem; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: translateY(20px); transition: transform 0.3s ease; text-align: center; border: 1px solid var(--border); }
        .update-modal-overlay.show .update-modal { transform: translateY(0); }
        .update-icon { width: 3rem; height: 3rem; background: #fef3c7; color: #d97706; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; }
        .update-title { font-size: 1.25rem; font-weight: 800; color: var(--brand); margin-bottom: 0.5rem; }
        .update-desc { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.5; }
        .update-version { background: var(--brand-soft); color: var(--brand); padding: 0.2rem 0.5rem; border-radius: 0.3rem; font-family: monospace; font-weight: 700; }
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

        // Define orgSlug globally for the view
        $orgSlug = $currentOrg?->slug ?? $user->organization?->slug ?? request()->route('org_slug');

        // Classrooms link — teachers & students always go to the central aggregate list
        if ($isStudentOrTeacher) {
            $classroomsLink = $isTenantContext ? $centralBase . '/rooms' : route('rooms.index');
        } else {
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
                <a href="{{ $isTenantContext ? $centralBase . '/admin/monitoring' : route('admin.monitoring') }}" class="nav-link {{ request()->is('admin/monitoring*') ? 'active' : '' }}">Users</a>
                <a href="#" onclick="document.getElementById('superadmin-gcash-modal').style.display='flex'; return false;" class="nav-link">GCash QR Code</a>
                <a href="{{ $isTenantContext ? $centralBase . '/admin/support' : route('admin.support.index') }}" class="nav-link {{ request()->is('admin/support*') ? 'active' : '' }}">Help and Support</a>
                <a href="#" onclick="document.getElementById('updateModal').classList.add('show'); return false;" class="nav-link">System Updates</a>
            @elseif($user->isTeacher())
                <a href="{{ $dashboardLink }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ $classroomsLink }}" class="nav-link {{ request()->is('rooms*') || request()->is('org/*/rooms*') ? 'active' : '' }}">Classrooms</a>
                <a href="{{ $archivedRoute }}" class="nav-link {{ request()->is('archived') ? 'active' : '' }}">Archived</a>
                @if($currentOrg && $currentOrg->status !== 'active')
                    <a href="{{ $isTenantContext ? $centralBase . '/org/' . $currentOrg->slug . '/subscription/payment' : route('org.subscription.payment', $currentOrg->slug) }}" class="nav-link {{ request()->is('org/*/subscription/payment*') ? 'active' : '' }}">Pay Subscription</a>
                @endif
                <a href="{{ $isTenantContext ? $centralBase . '/support' : route('support.index') }}" class="nav-link {{ request()->is('support*') ? 'active' : '' }}">Help and Support</a>
                <a href="#" onclick="document.getElementById('updateModal').classList.add('show'); return false;" class="nav-link">System Updates</a>
            @elseif($user->role === 'org_admin')
                <a href="{{ $dashboardLink }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ $classroomsLink }}" class="nav-link {{ request()->is('rooms*') || request()->is('org/*/rooms*') ? 'active' : '' }}">Classrooms</a>
                <a href="{{ $isTenantContext ? '/team' : ($orgSlug ? route('org.team', ['tenant' => $orgSlug]) : '#') }}" class="nav-link {{ request()->is('team*') ? 'active' : '' }}">Teams</a>
                @if($isTenantContext)
                    <a href="#" onclick="document.getElementById('gcash-qr-modal').style.display='flex'; return false;" class="nav-link">GCash QR Code</a>
                @endif
                @if($currentOrg && $currentOrg->status !== 'active')
                    <a href="{{ $isTenantContext ? $centralBase . '/org/' . $currentOrg->slug . '/subscription/payment' : route('org.subscription.payment', $currentOrg->slug) }}" class="nav-link {{ request()->is('org/*/subscription/payment*') ? 'active' : '' }}">Pay Subscription</a>
                @endif
                <a href="{{ $isTenantContext ? $centralBase . '/support' : route('support.index') }}" class="nav-link {{ request()->is('support*') ? 'active' : '' }}">Help and Support</a>
                <a href="#" onclick="document.getElementById('updateModal').classList.add('show'); return false;" class="nav-link">System Updates</a>
            @else
                {{-- Student and other roles --}}
                <a href="{{ $dashboardLink }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ $classroomsLink }}" class="nav-link {{ request()->is('rooms') && !request()->is('rooms/explore') ? 'active' : '' }}">My Classrooms</a>
                <a href="{{ $isTenantContext ? $centralBase . '/rooms/explore' : route('rooms.explore') }}" class="nav-link {{ request()->is('rooms/explore') ? 'active' : '' }}">Explore Classrooms</a>
                @if($currentOrg && $currentOrg->status !== 'active')
                    <a href="{{ $isTenantContext ? $centralBase . '/org/' . $currentOrg->slug . '/subscription/payment' : route('org.subscription.payment', $currentOrg->slug) }}" class="nav-link {{ request()->is('org/*/subscription/payment*') ? 'active' : '' }}">Pay Subscription</a>
                @endif
                <a href="{{ $isTenantContext ? $centralBase . '/support' : route('support.index') }}" class="nav-link {{ request()->is('support*') ? 'active' : '' }}">Help and Support</a>
                <a href="#" onclick="document.getElementById('updateModal').classList.add('show'); return false;" class="nav-link">System Updates</a>
            @endif
        </nav>
    </aside>
    @endauth

    <div class="wrapper {{ auth()->check() ? 'with-sidebar' : '' }}">
        <header class="{{ auth()->check() ? 'header-auth' : 'header-public' }}">
            @if(auth()->check())
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    {{-- Notifications Bell --}}
                    @php
                        $unreadNotificationsCount = auth()->user()->notifications()->whereNull('read_at')->count();
                    @endphp
                    <a href="{{ (function_exists('tenant') && tenant()) ? route('org.notifications.index') : route('notifications.index') }}" style="position: relative; color: var(--brand); text-decoration: none; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: var(--surface); transition: 0.2s;" onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='var(--surface)';">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        @if($unreadNotificationsCount > 0)
                            <span style="position: absolute; top: 0; right: 0; background: var(--danger, #dc2626); color: white; font-size: 0.65rem; font-weight: 800; min-width: 18px; height: 18px; border-radius: 99px; display: flex; align-items: center; justify-content: center; border: 2px solid white; transform: translate(25%, -25%);">
                                {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                            </span>
                        @endif
                    </a>

                    <div class="user-dropdown">
                    <button class="dropdown-trigger" id="profileBtn">
                        <span style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">{{ auth()->user()->name }}</span>
                        <img src="{{ auth()->user()->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=f59e0b&color=0f172a' }}" alt="Avatar" class="avatar">
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

        @php
            $currentVersion = 'v1.0.0';
            if (tenant()) {
                $currentVersion = \Illuminate\Support\Facades\Cache::get('tenant_version_' . tenant('id'), 'v1.0.0');
            } else {
                $currentVersion = \Illuminate\Support\Facades\Cache::get('central_version', 'v1.0.0');
            }
        @endphp

        <footer class="app-footer">
            &copy; {{ date('Y') }} LinkLearn. All rights reserved.
            @auth
            @if(isset($currentVersion))
                <span style="margin-left: 0.5rem;">Version: <strong style="color: var(--brand);" id="footerVersion">{{ $currentVersion }}</strong></span>
            @endif
            @endauth
        </footer>
    </div>

    @auth
    @if(isset($appVersion) && isset($currentVersion))
    <div class="update-modal-overlay" id="updateModal">
        <div class="update-modal">
            <div class="update-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </div>
            @if($appVersion !== $currentVersion)
                <h3 class="update-title">New Update Available!</h3>
                <p class="update-desc">
                    We've just released <span class="update-version" id="updateVersionDisplay">{{ $appVersion }}</span>. <br>Enjoy the new features and improvements!
                </p>
                <div style="display: flex; gap: 0.5rem; flex-direction: column;">
                    @php
                        $updateRoute = tenant() ? route('org.system.update', ['version' => $appVersion, 'tenant' => tenant('slug')]) : route('system.update', ['version' => $appVersion]);
                        $canUpdate = false;
                        if (tenant()) {
                            $canUpdate = auth()->check() && auth()->user()->role === 'org_admin';
                        } else {
                            $canUpdate = auth()->check() && auth()->user()->isSuperAdmin();
                        }
                    @endphp
                    @if($canUpdate)
                        <a href="{{ $updateRoute }}" class="btn btn-primary" style="width: 100%; text-align: center;">Update Now</a>
                    @else
                        <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem;">Please wait for your administrator to install the update.</p>
                    @endif
                    <button class="btn" style="width: 100%; background: transparent; color: var(--text-muted); padding: 0.5rem;" id="updateLaterBtn">Update Later</button>
                </div>
            @else
                <h3 class="update-title">System is Up to Date</h3>
                <p class="update-desc">
                    You are currently running the latest version <span class="update-version">{{ $currentVersion }}</span>. No updates are required at this time.
                </p>
                <div style="display: flex; gap: 0.5rem; flex-direction: column;">
                    <button class="btn btn-primary" style="width: 100%; text-align: center;" onclick="document.getElementById('updateModal').classList.remove('show')">Close</button>
                </div>
            @endif
        </div>
    </div>
    @endif
    @endauth

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

        const updateModal = document.getElementById('updateModal');
        const updateLaterBtn = document.getElementById('updateLaterBtn');
        const currentVersion = "{{ $appVersion ?? 'v1.0.0' }}";
        const isUpdateAvailable = {{ (isset($appVersion) && isset($currentVersion) && $appVersion !== $currentVersion) ? 'true' : 'false' }};

        if (updateModal && isUpdateAvailable) {
            const localDismissed = localStorage.getItem('linklearn_update_dismissed');
            if (localDismissed !== currentVersion) {
                setTimeout(() => {
                    updateModal.classList.add('show');
                }, 500);
            }
        }

        if (updateLaterBtn) {
            updateLaterBtn.addEventListener('click', () => {
                localStorage.setItem('linklearn_update_dismissed', currentVersion);
                updateModal.classList.remove('show');
            });
        }
    </script>

@auth
@if((function_exists('tenant') && tenant()) && auth()->user()->role === 'org_admin')
@php $gcashOrg = tenant(); @endphp
{{-- GCash QR Code Management Modal --}}
<div id="gcash-qr-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #fff; width: 100%; max-width: 420px; border-radius: 1rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,.25);">
        <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: var(--brand);">
            <div>
                <h3 style="font-weight: 800; color: #fff; margin: 0; font-size: 1.1rem;">💳 GCash QR Code</h3>
                <p style="color: rgba(255,255,255,0.8); font-size: 0.8rem; margin: 0.25rem 0 0;">Manage your organization's payment QR</p>
            </div>
            <button onclick="document.getElementById('gcash-qr-modal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; color: #fff; font-size: 1.2rem; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>
        <div style="padding: 1.5rem;">
            @if($gcashOrg->gcash_qr_code)
                <div style="margin-bottom: 1.25rem; text-align: center;">
                    <p style="font-size: 0.8rem; color: #64748b; font-weight: 600; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Current QR Code</p>
                    @php
                        $centralDomains = config('tenancy.central_domains', ['localhost']);
                        $centralDomain  = $centralDomains[0];
                        $port = request()->getPort();
                        $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
                        $qrUrl = request()->getScheme() . '://' . $centralDomain . $portStr . '/org-qr/' . $gcashOrg->slug;
                    @endphp
                    <img src="{{ $qrUrl }}?v={{ time() }}" alt="GCash QR Code" style="max-width: 200px; border-radius: 0.75rem; border: 3px solid var(--accent); padding: 0.5rem;">
                </div>
            @else
                <div style="border: 2px dashed #e2e8f0; border-radius: 0.75rem; padding: 2rem; text-align: center; margin-bottom: 1.25rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">📷</div>
                    <p style="color: #94a3b8; font-size: 0.85rem; font-weight: 600;">No GCash QR Code uploaded yet</p>
                </div>
            @endif

            <form action="{{ route('org.gcash.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ $gcashOrg->gcash_qr_code ? '🔄 Replace QR Code' : '⬆️ Upload QR Code' }}
                </label>
                <input type="file" name="gcash_qr_code" accept="image/*" required style="width: 100%; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.85rem;">
                <button type="submit" style="width: 100%; padding: 0.75rem; background: var(--accent); color: var(--brand); border: none; border-radius: 0.75rem; font-weight: 800; font-size: 0.9rem; cursor: pointer;">Save QR Code</button>
            </form>
        </div>
    </div>
</div>
@endif

@if($user->isSuperAdmin())
{{-- Super Admin GCash QR Code Management Modal --}}
<div id="superadmin-gcash-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #fff; width: 100%; max-width: 420px; border-radius: 1rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,.25);">
        <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: var(--brand);">
            <div>
                <h3 style="font-weight: 800; color: #fff; margin: 0; font-size: 1.1rem;">💳 Platform GCash QR Code</h3>
                <p style="color: rgba(255,255,255,0.8); font-size: 0.8rem; margin: 0.25rem 0 0;">For tenant subscription payments</p>
            </div>
            <button onclick="document.getElementById('superadmin-gcash-modal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; color: #fff; font-size: 1.2rem; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>
        <div style="padding: 1.5rem;">
            @if(file_exists(storage_path('app/public/admin/gcash_qr.png')))
                <div style="margin-bottom: 1.25rem; text-align: center;">
                    <p style="font-size: 0.8rem; color: #64748b; font-weight: 600; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Current QR Code</p>
                    <img src="{{ asset('storage/admin/gcash_qr.png') }}?v={{ time() }}" alt="GCash QR Code" style="max-width: 200px; border-radius: 0.75rem; border: 3px solid var(--accent); padding: 0.5rem;">
                </div>
            @else
                <div style="border: 2px dashed #e2e8f0; border-radius: 0.75rem; padding: 2rem; text-align: center; margin-bottom: 1.25rem;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">📷</div>
                    <p style="color: #94a3b8; font-size: 0.85rem; font-weight: 600;">No GCash QR Code uploaded yet</p>
                </div>
            @endif

            <form action="{{ route('admin.gcash.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                    {{ file_exists(storage_path('app/public/admin/gcash_qr.png')) ? '🔄 Replace QR Code' : '⬆️ Upload QR Code' }}
                </label>
                <input type="file" name="gcash_qr_code" accept="image/*" required style="width: 100%; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.85rem;">
                <button type="submit" style="width: 100%; padding: 0.75rem; background: var(--accent); color: var(--brand); border: none; border-radius: 0.75rem; font-weight: 800; font-size: 0.9rem; cursor: pointer;">Save QR Code</button>
            </form>
        </div>
    </div>
</div>
@endif
@endauth

</body>
</html>

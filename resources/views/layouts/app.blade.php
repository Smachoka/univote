<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UniVote') – University Voting System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin:0; padding:0; }

        #sidebar {
            width: 220px; min-height: 100vh;
            background: #fa6150;
            transition: width 0.25s ease;
            flex-shrink: 0; overflow: hidden;
        }
        #sidebar.collapsed { width: 0; }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 18px;
            color: rgba(255,255,255,0.82);
            font-size: 0.875rem; font-weight: 500;
            border-left: 3px solid transparent;
            transition: background 0.15s, border-color 0.15s, color 0.15s;
            white-space: nowrap; text-decoration: none; cursor: pointer;
            background: none; border-top: none; border-right: none; border-bottom: none;
            width: 100%; text-align: left;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(0,0,0,0.18);
            border-left-color: #fff;
            color: #fff;
        }
        .sidebar-link i { width: 18px; text-align: center; font-size: 0.9rem; }

        .sidebar-section {
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,0.45);
            padding: 18px 18px 6px;
            text-transform: uppercase;
        }

        #topbar { height: 56px; background: #c0392b; }
        #content { flex: 1; min-width: 0; background: #f4f6f9; min-height: 100vh; display: flex; flex-direction: column; }

        .flash-success { background:#d4edda; border:1px solid #c3e6cb; color:#155724; }
        .flash-error   { background:#f8d7da; border:1px solid #f5c6cb; color:#721c24; }
        .flash-info    { background:#d1ecf1; border:1px solid #bee5eb; color:#0c5460; }
        .flash-warning { background:#fff3cd; border:1px solid #ffeeba; color:#856404; }
    </style>
</head>
<body style="display:flex;">

{{-- ── SIDEBAR ── --}}
<aside id="sidebar">
    <div style="height:56px;background:rgba(0,0,0,0.15);display:flex;align-items:center;gap:10px;padding:0 16px;">
        <div style="width:32px;height:32px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;">
            <i class="fa-solid fa-check-to-slot" style="color:#c0392b;font-size:14px;"></i>
        </div>
        <span style="color:white;font-weight:700;font-size:1.1rem;letter-spacing:0.03em;">UniVote</span>
    </div>

    <nav style="margin-top:8px;padding-bottom:32px;">
        <div class="sidebar-section">Menus</div>

        @auth
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>

            <a href="{{ route('admin.elections.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.elections.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box-ballot"></i> Elections
            </a>

            @if(request()->route('election'))
                <a href="{{ route('admin.elections.positions.index', request()->route('election')) }}"
                   class="sidebar-link {{ request()->routeIs('admin.elections.positions.*') ? 'active' : '' }}"
                   style="padding-left:38px;font-size:0.82rem;">
                    <i class="fa-solid fa-list-ul"></i> Positions
                </a>
            @endif

            <a href="{{ route('admin.students.import.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.students.import.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-import"></i> Import Students
            </a>

            <div class="sidebar-section" style="margin-top:4px;">Results</div>
            @foreach(\App\Models\Election::where('status','!=','draft')->latest()->take(5)->get() as $el)
                <a href="{{ route('admin.elections.results', $el) }}"
                   class="sidebar-link" style="padding-left:38px;font-size:0.82rem;">
                    <i class="fa-solid fa-chart-column"></i>
                    {{ \Illuminate\Support\Str::limit($el->title, 18) }}
                </a>
            @endforeach

            {{-- ── SETTINGS ── --}}
            <div class="sidebar-section" style="margin-top:4px;">System</div>
            <a href="{{ route('admin.settings.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i> Settings
            </a>

        @else
            <a href="{{ route('student.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
            @php $activeEl = \App\Models\Election::where('status','active')->first(); @endphp
            @if($activeEl)
                <a href="{{ route('student.vote', $activeEl) }}"
                   class="sidebar-link {{ request()->routeIs('student.vote') ? 'active' : '' }}">
                    <i class="fa-solid fa-vote-yea"></i> Vote Now
                </a>
            @endif
        @endif

        <div class="sidebar-section" style="margin-top:4px;">Account</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
        @endauth
    </nav>
</aside>

{{-- ── MAIN ── --}}
<div id="content">

    {{-- TOP BAR --}}
    <header id="topbar" style="display:flex;align-items:center;justify-content:space-between;padding:0 20px;position:sticky;top:0;z-index:30;">
        <div style="display:flex;align-items:center;gap:16px;">
            <button onclick="toggleSidebar()"
                    style="background:none;border:none;color:white;font-size:1.1rem;width:36px;height:36px;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        @auth
        <div style="position:relative;" id="userMenuWrap">
            <button onclick="toggleUserMenu()"
                    style="display:flex;align-items:center;gap:8px;background:rgba(0,0,0,0.15);border:none;color:white;padding:6px 12px;border-radius:6px;cursor:pointer;">
                <div style="width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,0.25);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;color:white;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span style="font-size:0.85rem;font-weight:500;">{{ auth()->user()->name }}</span>
                <i class="fa-solid fa-chevron-down" style="font-size:0.7rem;opacity:0.7;"></i>
            </button>

            <div id="userMenu"
                 style="display:none;position:absolute;right:0;top:48px;background:white;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,0.12);border:1px solid #eee;min-width:190px;z-index:50;">
                <div style="padding:10px 14px;border-bottom:1px solid #f0f0f0;">
                    <p style="font-size:0.8rem;font-weight:600;color:#333;margin:0;">{{ auth()->user()->name }}</p>
                    <p style="font-size:0.75rem;color:#999;margin:2px 0 0;">{{ ucfirst(auth()->user()->role) }}</p>
                </div>

                {{-- Settings link in dropdown (admin only) --}}
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.settings.index') }}"
                       style="display:flex;align-items:center;gap:8px;padding:10px 14px;font-size:0.85rem;color:#333;text-decoration:none;border-bottom:1px solid #f5f5f5;transition:background 0.15s;"
                       onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='white'">
                        <i class="fa-solid fa-gear" style="color:#888;width:16px;text-align:center;"></i> Settings
                    </a>
                    <a href="{{ route('admin.students.import.index') }}"
                       style="display:flex;align-items:center;gap:8px;padding:10px 14px;font-size:0.85rem;color:#333;text-decoration:none;border-bottom:1px solid #f5f5f5;transition:background 0.15s;"
                       onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='white'">
                        <i class="fa-solid fa-file-import" style="color:#888;width:16px;text-align:center;"></i> Import Students
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            style="width:100%;text-align:left;padding:10px 14px;font-size:0.85rem;color:#c0392b;background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </header>

    {{-- PAGE HEADING BAR --}}
    <div style="background:white;padding:14px 24px;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between;">
        <h1 style="font-size:1.2rem;font-weight:600;color:#333;margin:0;">@yield('title', 'Dashboard')</h1>
        @hasSection('breadcrumb')
        <nav style="font-size:0.8rem;color:#999;">@yield('breadcrumb')</nav>
        @endif
    </div>

    {{-- FLASH MESSAGES --}}
    <div style="padding:12px 24px 0;">
        @foreach(['success','error','info','warning'] as $type)
            @if(session($type))
                <div class="flash-{{ $type }}" style="border-radius:6px;padding:10px 14px;margin-bottom:8px;font-size:0.85rem;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid {{ $type === 'success' ? 'fa-circle-check' : ($type === 'error' ? 'fa-circle-xmark' : 'fa-circle-info') }}"></i>
                    {{ session($type) }}
                </div>
            @endif
        @endforeach
        @if($errors->any())
            <div class="flash-error" style="border-radius:6px;padding:10px 14px;margin-bottom:8px;font-size:0.85rem;">
                @foreach($errors->all() as $error)
                    <div><i class="fa-solid fa-triangle-exclamation" style="margin-right:4px;"></i>{{ $error }}</div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- PAGE CONTENT --}}
    <main style="padding:20px 24px;flex:1;">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer style="padding:10px 24px;font-size:0.75rem;color:#aaa;border-top:1px solid #eee;background:white;display:flex;justify-content:space-between;">
        <span>Copyright &copy; {{ date('Y') }}</span>
        <span>1.0</span>
    </footer>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('collapsed');
    }
    function toggleUserMenu() {
        const m = document.getElementById('userMenu');
        m.style.display = m.style.display === 'none' ? 'block' : 'none';
    }
    document.addEventListener('click', function(e) {
        const wrap = document.getElementById('userMenuWrap');
        const menu = document.getElementById('userMenu');
        if (wrap && !wrap.contains(e.target) && menu) {
            menu.style.display = 'none';
        }
    });
</script>
</body>
</html>
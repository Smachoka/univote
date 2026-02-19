<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UniVote') – University Voting System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #fff; color: #333; }
        a { text-decoration: none; color: inherit; }

        /* ── TOP WHITE BAR ── */
        .top-bar {
            background: #fff;
            padding: 0 40px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f0f0f0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            background: #c0392b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-icon i { color: white; font-size: 1.1rem; }
        .logo-text { font-size: 1.4rem; font-weight: 800; color: #c0392b; letter-spacing: -0.02em; }
        .logo-text span { color: #333; }

        .top-search {
            display: flex;
            align-items: center;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
            max-width: 340px;
            width: 100%;
        }
        .top-search input {
            border: none;
            outline: none;
            padding: 8px 14px;
            font-size: 0.85rem;
            flex: 1;
            color: #555;
        }
        .top-search button {
            background: #c0392b;
            border: none;
            padding: 8px 14px;
            cursor: pointer;
            color: white;
            font-size: 0.9rem;
        }

        /* ── RED NAV BAR ── */
        .main-nav {
            background: #c0392b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
        }
        .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 13px 28px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
            border-bottom: 3px solid transparent;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(0,0,0,0.15);
            color: white;
            border-bottom-color: white;
        }

        /* ── FOOTER ── */
        .site-footer {
            background: #f8f8f8;
            border-top: 1px solid #eee;
            padding: 30px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .footer-links { display: flex; gap: 24px; }
        .footer-links a { font-size: 0.85rem; color: #555; }
        .footer-links a:hover { color: #c0392b; }
        .footer-copy { font-size: 0.78rem; color: #aaa; }

        /* ── UTILITIES ── */
        .btn-red {
            background: #c0392b;
            color: white;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }
        .btn-red:hover { background: #a93226; }
        .btn-outline {
            background: transparent;
            color: white;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            border: 2px solid white;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }
        .btn-outline:hover { background: rgba(255,255,255,0.15); }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #222;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title::before {
            content: '';
            display: inline-block;
            width: 5px;
            height: 22px;
            background: #c0392b;
            border-radius: 2px;
        }

        @media (max-width: 768px) {
            .top-bar { padding: 0 16px; }
            .top-search { display: none; }
            .nav-link { padding: 12px 14px; font-size: 0.8rem; }
            .site-footer { padding: 20px 16px; }
        }
    </style>
</head>
<body>

{{-- ══════ TOP WHITE BAR ══════ --}}
<header class="top-bar">
    {{-- Logo --}}
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon"><i class="fa-solid fa-check-to-slot"></i></div>
        <span class="logo-text">Uni<span>Vote</span></span>
    </a>

    {{-- Search --}}
    <div class="top-search">
        <input type="text" placeholder="Search candidates, positions...">
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    {{-- Auth Button --}}
    @auth
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}"
           class="btn-red">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
    @else
        <a href="{{ route('login') }}" class="btn-red">
            <i class="fa-solid fa-right-to-bracket"></i> Sign In
        </a>
    @endauth
</header>

{{-- ══════ RED NAV BAR ══════ --}}
<nav class="main-nav">
    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i> Home
    </a>
    <a href="{{ route('home') }}#candidates" class="nav-link">
        <i class="fa-solid fa-users"></i> Candidates
    </a>
    <a href="{{ route('home') }}#positions" class="nav-link">
        <i class="fa-solid fa-sitemap"></i> Positions
    </a>
    <a href="{{ route('home') }}#about" class="nav-link">
        <i class="fa-solid fa-circle-info"></i> About
    </a>
    @guest
    <a href="{{ route('login') }}" class="nav-link">
        <i class="fa-solid fa-lock"></i> Sign In
    </a>
    @endguest
</nav>

{{-- ══════ PAGE CONTENT ══════ --}}
@yield('content')

{{-- ══════ FOOTER ══════ --}}
<footer class="site-footer">
    <div class="footer-links">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('home') }}#candidates">Candidates</a>
        <a href="{{ route('home') }}#positions">Positions</a>
        <a href="{{ route('login') }}">Student Login</a>
    </div>
    <span class="footer-copy">Copyright &copy; {{ date('Y') }} University Voting System</span>
</footer>

</body>
</html>
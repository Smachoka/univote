<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UniVote') — UniVote</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #faf9f5;
            color: #0a0a0f;
            -webkit-font-smoothing: antialiased;
        }
        img { max-width: 100%; display: block; }
        a { color: inherit; }
    </style>

    @stack('styles')
</head>
<body>

    @yield('content')

    {{-- Footer --}}
    <footer style="background:#0a0a0f;padding:28px 48px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <span style="font-family:'Bebas Neue',sans-serif;font-size:1.1rem;color:white;letter-spacing:0.08em;">
            UNI<span style="color:#d42b2b;">VOTE</span>
        </span>
        <span style="font-size:0.75rem;color:rgba(255,255,255,0.3);font-family:'DM Mono',monospace;">
            © {{ date('Y') }} University Voting System. All rights reserved.
        </span>
        <div style="display:flex;gap:16px;">
            <a href="{{ route('login') }}" style="font-size:0.75rem;color:rgba(255,255,255,0.4);text-decoration:none;font-family:'DM Mono',monospace;">
                Student Login
            </a>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
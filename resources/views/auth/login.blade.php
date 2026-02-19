<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — UniVote</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:      #0a0a0f;
            --red:      #d42b2b;
            --red-dark: #a81f1f;
            --gold:     #c9a84c;
            --muted:    rgba(255,255,255,0.45);
            --border:   rgba(255,255,255,0.08);
            --font-d:   'Bebas Neue', sans-serif;
            --font-b:   'DM Sans', sans-serif;
            --font-m:   'DM Mono', monospace;
        }

        body {
            font-family: var(--font-b);
            background: var(--ink);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            -webkit-font-smoothing: antialiased;
        }

        /* ── LEFT PANEL ── */
        .left {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 52px 56px;
            background: var(--ink);
        }

        .left-bg {
            position: absolute; inset: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 70% 60% at 30% 60%, rgba(212,43,43,0.2) 0%, transparent 65%),
                radial-gradient(ellipse 40% 40% at 80% 20%, rgba(201,168,76,0.07) 0%, transparent 60%);
        }
        .left-grid {
            position: absolute; inset: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 52px 52px;
        }
        .left-vline {
            position: absolute; top: 0; right: 0; bottom: 0;
            width: 1px; background: var(--border);
        }

        .left-logo {
            position: relative; z-index: 10;
            font-family: var(--font-d);
            font-size: 1.4rem; letter-spacing: 0.1em;
            color: white; text-decoration: none;
        }
        .left-logo span { color: var(--red); }

        .left-main {
            position: relative; z-index: 10;
        }
        .left-eyebrow {
            font-family: var(--font-m); font-size: 0.68rem;
            color: var(--gold); letter-spacing: 0.14em; text-transform: uppercase;
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 24px;
        }
        .left-eyebrow::before { content: ''; width: 28px; height: 1px; background: var(--gold); }

        .left-h1 {
            font-family: var(--font-d);
            font-size: clamp(3.5rem, 5.5vw, 6rem);
            line-height: 0.88; letter-spacing: 0.02em;
            color: white; margin-bottom: 28px;
        }
        .left-h1 .red { color: var(--red); display: block; }

        .left-desc {
            font-size: 0.92rem; line-height: 1.75;
            color: var(--muted); max-width: 360px;
        }

        .left-bottom {
            position: relative; z-index: 10;
        }
        .left-stats {
            display: flex; gap: 32px;
        }
        .lstat-n {
            font-family: var(--font-d); font-size: 2rem;
            color: white; line-height: 1; letter-spacing: 0.02em;
        }
        .lstat-l {
            font-family: var(--font-m); font-size: 0.6rem;
            color: rgba(255,255,255,0.3); text-transform: uppercase;
            letter-spacing: 0.1em; margin-top: 3px;
        }

        /* decorative ballot lines */
        .ballot-lines {
            position: absolute; bottom: 52px; right: 56px; z-index: 10;
            display: flex; flex-direction: column; gap: 8px; opacity: 0.15;
        }
        .bl { height: 2px; background: white; border-radius: 2px; }

        /* ── RIGHT PANEL ── */
        .right {
            display: flex; align-items: center; justify-content: center;
            padding: 52px 64px;
            background: #111118;
        }

        .form-wrap {
            width: 100%; max-width: 400px;
            animation: fadeUp 0.6s 0.1s ease both;
        }

        .form-top {
            margin-bottom: 40px;
        }
        .form-tag {
            font-family: var(--font-m); font-size: 0.65rem;
            color: var(--red); text-transform: uppercase; letter-spacing: 0.14em;
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 12px;
        }
        .form-tag::after { content: ''; width: 32px; height: 1px; background: var(--red); }

        .form-h2 {
            font-family: var(--font-d);
            font-size: 2.6rem; letter-spacing: 0.03em;
            color: white; line-height: 0.95;
        }
        .form-h2 span { color: rgba(255,255,255,0.25); font-size: 0.6em; display: block; margin-top: 6px; letter-spacing: 0.06em; }

        /* Error */
        .error-box {
            display: flex; align-items: flex-start; gap: 10px;
            background: rgba(212,43,43,0.1);
            border: 1px solid rgba(212,43,43,0.3);
            border-radius: 8px; padding: 12px 14px;
            margin-bottom: 28px;
        }
        .error-box i { color: var(--red); font-size: 0.85rem; margin-top: 1px; flex-shrink: 0; }
        .error-box span { font-size: 0.82rem; color: rgba(255,255,255,0.7); line-height: 1.5; }

        /* Form fields */
        .field { margin-bottom: 20px; }
        .field-label {
            font-family: var(--font-m); font-size: 0.65rem;
            color: rgba(255,255,255,0.4); text-transform: uppercase;
            letter-spacing: 0.1em; margin-bottom: 8px; display: block;
        }
        .field-wrap {
            position: relative;
        }
        .field-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: rgba(255,255,255,0.25); font-size: 0.85rem; pointer-events: none;
        }
        .field-input {
            width: 100%; padding: 13px 14px 13px 40px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 7px; color: white;
            font-size: 0.88rem; font-family: var(--font-b);
            transition: all 0.2s; outline: none;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.2); }
        .field-input:focus {
            border-color: var(--red);
            background: rgba(212,43,43,0.06);
            box-shadow: 0 0 0 3px rgba(212,43,43,0.1);
        }
        .field-input.error { border-color: rgba(212,43,43,0.5); }

        /* Password toggle */
        .pw-toggle {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            color: rgba(255,255,255,0.25); cursor: pointer; font-size: 0.85rem;
            transition: color 0.2s; background: none; border: none; padding: 0;
        }
        .pw-toggle:hover { color: rgba(255,255,255,0.6); }

        /* Remember row */
        .remember-row {
            display: flex; align-items: center; gap: 9px;
            margin-bottom: 28px;
        }
        .remember-check {
            width: 17px; height: 17px; border-radius: 4px;
            border: 1.5px solid rgba(255,255,255,0.2);
            background: transparent; cursor: pointer;
            accent-color: var(--red);
        }
        .remember-label {
            font-size: 0.82rem; color: rgba(255,255,255,0.4);
            cursor: pointer;
        }

        /* Submit */
        .submit-btn {
            width: 100%; padding: 14px;
            background: var(--red); color: white;
            border: none; border-radius: 7px;
            font-family: var(--font-b); font-size: 0.9rem; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 9px;
            letter-spacing: 0.01em;
        }
        .submit-btn:hover {
            background: var(--red-dark);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(212,43,43,0.3);
        }
        .submit-btn:active { transform: translateY(0); }

        /* Footer note */
        .form-note {
            margin-top: 24px; text-align: center;
            font-family: var(--font-m); font-size: 0.65rem;
            color: rgba(255,255,255,0.2); letter-spacing: 0.06em;
            line-height: 1.6;
        }

        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-family: var(--font-m); font-size: 0.65rem;
            color: rgba(255,255,255,0.25); text-decoration: none;
            text-transform: uppercase; letter-spacing: 0.1em;
            transition: color 0.2s; margin-top: 20px;
        }
        .back-link:hover { color: rgba(255,255,255,0.55); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 780px) {
            body { grid-template-columns: 1fr; }
            .left { display: none; }
            .right { padding: 40px 28px; min-height: 100vh; }
        }
    </style>
</head>
<body>

    {{-- ══ LEFT PANEL ══ --}}
    <div class="left">
        <div class="left-bg"></div>
        <div class="left-grid"></div>
        <div class="left-vline"></div>

        <a href="{{ route('home') }}" class="left-logo">UNI<span>VOTE</span></a>

        <div class="left-main">
            <div class="left-eyebrow">Secure Campus Elections</div>
            <h1 class="left-h1">
                <span class="red">Cast Your</span>
                Vote
            </h1>
            <p class="left-desc">
                Your university's trusted platform for transparent, secure, and democratic student elections. Every vote counts.
            </p>
        </div>

        <div class="left-bottom">
            <div class="left-stats">
                <div>
                    <div class="lstat-n">100%</div>
                    <div class="lstat-l">Secure</div>
                </div>
                <div>
                    <div class="lstat-n">1 Vote</div>
                    <div class="lstat-l">Per Student</div>
                </div>
                <div>
                    <div class="lstat-n">Live</div>
                    <div class="lstat-l">Results</div>
                </div>
            </div>
        </div>

        <div class="ballot-lines">
            <div class="bl" style="width:120px;"></div>
            <div class="bl" style="width:80px;"></div>
            <div class="bl" style="width:100px;"></div>
            <div class="bl" style="width:60px;"></div>
        </div>
    </div>

    {{-- ══ RIGHT PANEL ══ --}}
    <div class="right">
        <div class="form-wrap">
            <div class="form-top">
                <div class="form-tag">Student Portal</div>
                <h2 class="form-h2">
                    Welcome Back
                    <span>Sign in to continue</span>
                </h2>
            </div>

            @if($errors->any())
                <div class="error-box">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email" class="field-label">Email Address</label>
                    <div class="field-wrap">
                        <i class="fa-solid fa-envelope field-icon"></i>
                        <input
                            id="email" name="email" type="email"
                            value="{{ old('email') }}"
                            placeholder="you@university.edu"
                            required autofocus
                            class="field-input @error('email') error @enderror"
                        >
                    </div>
                </div>

                <div class="field">
                    <label for="password" class="field-label">Password</label>
                    <div class="field-wrap">
                        <i class="fa-solid fa-lock field-icon"></i>
                        <input
                            id="password" name="password" type="password"
                            placeholder="••••••••"
                            required
                            class="field-input"
                        >
                        <button type="button" class="pw-toggle" onclick="togglePw()" id="pwToggle">
                            <i class="fa-solid fa-eye" id="pwIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember" class="remember-check"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="remember-label">Keep me signed in</label>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Sign In
                </button>
            </form>

            <p class="form-note">
                Can't access your account? Contact your election administrator.
            </p>

            <a href="{{ route('home') }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Back to homepage
            </a>
        </div>
    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('pwIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        }
    </script>

</body>
</html>
@extends('layouts.public')

@section('title', 'Welcome')

@section('content')

<style>
:root {
    --ink:        #0a0a0f;
    --ink-soft:   #141420;
    --surface:    #f4f3ef;
    --cream:      #faf9f5;
    --red:        #d42b2b;
    --red-dark:   #a81f1f;
    --gold:       #c9a84c;
    --muted:      #6b6b7a;
    --border-d:   rgba(255,255,255,0.07);
    --font-d:     'Bebas Neue', sans-serif;
    --font-b:     'DM Sans', sans-serif;
    --font-m:     'DM Mono', monospace;
}

/* ── NAVBAR ── */
.navbar {
    position: fixed; top: 0; left: 0; right: 0; z-index: 200;
    height: 62px;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 52px;
    background: rgba(10,10,15,0.88);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border-d);
}
.nav-logo {
    font-family: var(--font-d);
    font-size: 1.35rem; letter-spacing: 0.1em;
    color: white; text-decoration: none;
}
.nav-logo span { color: var(--red); }
.nav-links { display: flex; align-items: center; gap: 6px; }
.nav-link {
    padding: 7px 16px; border-radius: 50px;
    font-size: 0.82rem; font-weight: 500;
    text-decoration: none; transition: all 0.2s;
    color: rgba(255,255,255,0.55);
    font-family: var(--font-b);
}
.nav-link:hover { color: white; background: rgba(255,255,255,0.08); }
.nav-link-cta {
    background: var(--red); color: white !important;
    padding: 7px 20px; border-radius: 50px;
    font-weight: 600;
}
.nav-link-cta:hover { background: var(--red-dark) !important; }

/* ── HAMBURGER ── */
.hamburger {
    display: none; flex-direction: column; gap: 5px;
    cursor: pointer; background: none; border: none; padding: 6px; z-index: 300;
}
.hamburger span {
    display: block; width: 24px; height: 2px;
    background: white; border-radius: 2px; transition: all 0.3s ease;
}
.hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
.hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* ── MOBILE MENU ── */
.mobile-menu {
    display: none; position: fixed;
    top: 62px; left: 0; right: 0;
    background: rgba(10,10,15,0.97);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255,255,255,0.08);
    flex-direction: column;
    padding: 12px 20px 20px; gap: 4px;
    z-index: 199;
    animation: slideDown 0.25s ease;
}
.mobile-menu.open { display: flex; }
.mobile-menu .m-link {
    padding: 13px 16px; border-radius: 7px;
    font-size: 0.9rem; font-weight: 500;
    text-decoration: none; color: rgba(255,255,255,0.6);
    font-family: var(--font-b);
    border-bottom: 1px solid rgba(255,255,255,0.05);
    transition: all 0.2s;
}
.mobile-menu .m-link:hover { color: white; background: rgba(255,255,255,0.05); }
.mobile-menu .m-link:last-child { border-bottom: none; }
.mobile-menu .m-link-cta {
    margin-top: 8px; text-align: center;
    background: var(--red); color: white !important;
    font-weight: 600; border-bottom: none !important;
    border-radius: 7px;
}
.mobile-menu .m-link-cta:hover { background: var(--red-dark) !important; }

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

@media (max-width: 760px) {
    .hamburger { display: flex; }
    .navbar { padding: 0 24px; }
    .navbar .nav-links { display: none; }
}

/* ── HERO ── */
.hero {
    min-height: 100vh;
    background: var(--ink);
    display: grid;
    grid-template-columns: 1fr 420px;
    align-items: center;
    padding-top: 62px;
    position: relative;
    overflow: hidden;
}
.hero-bg {
    position: absolute; inset: 0; pointer-events: none;
    background:
        radial-gradient(ellipse 60% 60% at 70% 50%, rgba(212,43,43,0.12) 0%, transparent 70%),
        radial-gradient(ellipse 40% 50% at 20% 80%, rgba(201,168,76,0.06) 0%, transparent 60%);
}
.hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 56px 56px;
}
.hero-left {
    position: relative; z-index: 10;
    padding: 80px 64px 80px 72px;
}
.hero-tag {
    display: inline-flex; align-items: center; gap: 9px;
    font-family: var(--font-m); font-size: 0.68rem;
    color: var(--gold); letter-spacing: 0.14em; text-transform: uppercase;
    margin-bottom: 30px;
    opacity: 0; animation: up 0.5s 0.1s ease forwards;
}
.hero-tag::before { content:''; width:28px; height:1px; background:var(--gold); }
.dot-pulse {
    width: 7px; height: 7px; border-radius: 50%;
    background: #4ade80;
    animation: pulse-anim 2s infinite;
    display: inline-block;
}
.hero-h1 {
    font-family: var(--font-d);
    font-size: clamp(4.5rem, 7.5vw, 8rem);
    line-height: 0.88;
    color: white; letter-spacing: 0.02em;
    margin-bottom: 32px;
    opacity: 0; animation: up 0.6s 0.2s ease forwards;
}
.hero-h1 .red { color: var(--red); display: block; }
.hero-h1 .dim { color: rgba(255,255,255,0.38); font-size: 0.5em; display: block; margin-top: 6px; letter-spacing: 0.04em; }
.hero-p {
    font-size: 1rem; line-height: 1.8;
    color: rgba(255,255,255,0.48);
    max-width: 420px; margin-bottom: 44px;
    opacity: 0; animation: up 0.6s 0.32s ease forwards;
}
.hero-p strong { color: rgba(255,255,255,0.75); font-weight: 500; }
.hero-btns {
    display: flex; gap: 12px; flex-wrap: wrap;
    opacity: 0; animation: up 0.6s 0.42s ease forwards;
}
.btn-p {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 13px 28px; border-radius: 5px;
    font-size: 0.88rem; font-weight: 600;
    text-decoration: none; transition: all 0.2s;
    font-family: var(--font-b);
    background: var(--red); color: white;
    border: 2px solid var(--red);
}
.btn-p:hover { background: var(--red-dark); border-color: var(--red-dark); color: white; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(212,43,43,0.3); }
.btn-g {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 13px 28px; border-radius: 5px;
    font-size: 0.88rem; font-weight: 500;
    text-decoration: none; transition: all 0.2s;
    font-family: var(--font-b);
    background: transparent; color: rgba(255,255,255,0.6);
    border: 2px solid rgba(255,255,255,0.14);
}
.btn-g:hover { border-color: rgba(255,255,255,0.35); color: white; }

/* Hero ballot widget */
.hero-right {
    position: relative; z-index: 10;
    padding: 60px 52px 60px 0;
    opacity: 0; animation: up 0.7s 0.5s ease forwards;
}
.ballot-widget {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.09);
    border-radius: 14px; padding: 28px;
    backdrop-filter: blur(10px);
}
.bw-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 22px; padding-bottom: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}
.bw-title { font-family: var(--font-m); font-size: 0.68rem; color: var(--gold); text-transform: uppercase; letter-spacing: 0.1em; }
.bw-live { display: flex; align-items: center; gap: 6px; font-family: var(--font-m); font-size: 0.68rem; color: #4ade80; }
.bw-pos-label { font-family: var(--font-m); font-size: 0.65rem; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px; }
.bw-pos-block { margin-bottom: 18px; }
.bw-cand {
    display: flex; align-items: center; gap: 11px;
    padding: 11px 13px; border-radius: 8px; margin-bottom: 6px;
    border: 1px solid rgba(255,255,255,0.05);
    transition: all 0.2s; cursor: pointer;
}
.bw-cand:hover { background: rgba(255,255,255,0.04); }
.bw-cand.sel { background: rgba(212,43,43,0.14); border-color: rgba(212,43,43,0.35); }
.bw-av {
    width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, var(--red-dark), var(--red));
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-d); font-size: 1rem; color: white; letter-spacing: 0.04em;
}
.bw-nm { font-size: 0.85rem; font-weight: 500; color: white; }
.bw-dp { font-size: 0.7rem; color: rgba(255,255,255,0.35); margin-top: 1px; }
.bw-radio {
    margin-left: auto; width: 17px; height: 17px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.18); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.bw-cand.sel .bw-radio { border-color: var(--red); background: var(--red); }
.bw-cand.sel .bw-radio::after { content:''; width:5px; height:5px; border-radius:50%; background:white; }

/* ── STATS ── */
.stats {
    background: var(--red);
    display: grid; grid-template-columns: repeat(4, 1fr);
}
.stat {
    padding: 26px 16px; text-align: center;
    border-right: 1px solid rgba(255,255,255,0.14);
}
.stat:last-child { border-right: none; }
.stat-n { font-family: var(--font-d); font-size: 2.6rem; color: white; line-height: 1; letter-spacing: 0.02em; }
.stat-l { font-family: var(--font-m); font-size: 0.62rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px; }

/* ── CANDIDATES ── */
.cands-section { padding: 96px 72px; background: var(--cream); }
.sec-eyebrow {
    font-family: var(--font-m); font-size: 0.68rem;
    color: var(--red); text-transform: uppercase; letter-spacing: 0.14em;
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 10px;
}
.sec-eyebrow::after { content:''; width:48px; height:1px; background:var(--red); }
.sec-h2 {
    font-family: var(--font-d);
    font-size: clamp(2.4rem, 4vw, 4.2rem);
    color: var(--ink); line-height: 0.92;
    letter-spacing: 0.02em; margin-bottom: 56px;
}
.sec-h2 em { color: var(--red); font-style: normal; }

.pos-block { margin-bottom: 68px; }
.pos-row {
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 26px;
}
.pos-name-tag { font-family: var(--font-d); font-size: 1.7rem; color: var(--ink); letter-spacing: 0.04em; }
.pos-count-badge {
    font-family: var(--font-m); font-size: 0.66rem;
    color: var(--red); border: 1px solid var(--red);
    padding: 3px 11px; border-radius: 50px;
    text-transform: uppercase; letter-spacing: 0.08em;
}
.pos-line { flex: 1; height: 1px; background: rgba(0,0,0,0.08); }

.cands-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
    gap: 20px;
}

.cand-card {
    background: white; border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow: hidden; display: flex; flex-direction: column;
    transition: all 0.25s; position: relative;
}
.cand-card::after {
    content: ''; position: absolute;
    top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--red), var(--gold));
    opacity: 0; transition: opacity 0.25s;
}
.cand-card:hover { transform: translateY(-5px); box-shadow: 0 18px 44px rgba(0,0,0,0.11); }
.cand-card:hover::after { opacity: 1; }

.cand-top {
    background: var(--ink); padding: 30px 20px 22px;
    display: flex; flex-direction: column; align-items: center;
    position: relative; overflow: hidden;
}
.cand-top::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(circle at 50% 110%, rgba(212,43,43,0.18) 0%, transparent 65%);
}
.cand-av-img {
    width: 76px; height: 76px; border-radius: 50%;
    object-fit: cover; position: relative; z-index: 1;
    border: 3px solid rgba(255,255,255,0.12);
    box-shadow: 0 4px 18px rgba(0,0,0,0.35);
    margin-bottom: 12px;
}
.cand-av-init {
    width: 76px; height: 76px; border-radius: 50%;
    background: linear-gradient(135deg, var(--red-dark), var(--red));
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-d); font-size: 2rem; color: white;
    position: relative; z-index: 1;
    box-shadow: 0 4px 18px rgba(212,43,43,0.4);
    margin-bottom: 12px; letter-spacing: 0.04em;
}
.cand-pos-chip {
    font-family: var(--font-m); font-size: 0.62rem;
    color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.1em;
    position: relative; z-index: 1;
}
.cand-body { padding: 20px 22px; flex: 1; }
.cand-name {
    font-size: 1.05rem; font-weight: 700; color: var(--ink);
    text-align: center; margin-bottom: 12px;
}
.cand-manifesto {
    font-size: 0.81rem; line-height: 1.7; color: var(--muted);
    font-style: italic;
    background: var(--surface); border-left: 2px solid var(--red);
    padding: 10px 13px; border-radius: 0 6px 6px 0;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.cand-foot {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 22px;
    border-top: 1px solid rgba(0,0,0,0.05);
    background: #fafaf8;
}
.cand-pos-label { font-family: var(--font-m); font-size: 0.68rem; color: var(--muted); }
.cand-action {
    font-family: var(--font-m); font-size: 0.72rem;
    color: var(--red); text-decoration: none; font-weight: 500;
    text-transform: uppercase; letter-spacing: 0.06em;
    display: inline-flex; align-items: center; gap: 4px;
    transition: gap 0.2s;
}
.cand-action:hover { gap: 8px; color: var(--red-dark); }
.cand-action::after { content: '→'; }

/* No election placeholder */
.no-data {
    text-align: center; padding: 80px 20px;
    background: var(--surface); border-radius: 12px;
}
.no-data i { font-size: 3rem; color: #ccc; display: block; margin-bottom: 18px; }
.no-data h3 { font-family: var(--font-d); font-size: 2rem; color: #bbb; letter-spacing: 0.04em; margin-bottom: 8px; }
.no-data p { color: var(--muted); font-size: 0.88rem; }

/* ── POSITIONS ── */
.pos-section {
    background: var(--ink-soft); padding: 80px 72px;
}
.pos-section .sec-eyebrow { color: var(--gold); }
.pos-section .sec-eyebrow::after { background: var(--gold); }
.pos-section .sec-h2 { color: white; }
.pos-section .sec-h2 em { color: var(--gold); }

.pos-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 14px;
}
.pos-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 10px; padding: 22px;
    text-decoration: none; display: block;
    transition: all 0.22s;
}
.pos-card:hover {
    background: rgba(255,255,255,0.07);
    border-color: rgba(212,43,43,0.35);
    transform: translateY(-2px);
}
.pos-card-icon {
    width: 42px; height: 42px; border-radius: 8px;
    background: rgba(212,43,43,0.1); border: 1px solid rgba(212,43,43,0.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--red); font-size: 1rem; margin-bottom: 14px;
}
.pos-card-name { font-family: var(--font-d); font-size: 1.3rem; color: white; letter-spacing: 0.04em; margin-bottom: 4px; }
.pos-card-meta { font-family: var(--font-m); font-size: 0.65rem; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 12px; }
.pos-chips { display: flex; flex-wrap: wrap; gap: 5px; }
.pos-chip { font-size: 0.68rem; color: rgba(255,255,255,0.45); background: rgba(255,255,255,0.06); padding: 3px 9px; border-radius: 50px; }

/* ── HOW IT WORKS ── */
.how-section { background: var(--surface); padding: 96px 72px; }
.steps {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 0; margin-top: 56px; position: relative;
}
.steps::before {
    content: ''; position: absolute;
    top: 31px; left: 12%; right: 12%;
    height: 1px; background: rgba(0,0,0,0.09);
}
.step { text-align: center; padding: 0 24px; position: relative; z-index: 1; }
.step-num {
    width: 62px; height: 62px; border-radius: 50%;
    background: white; border: 1.5px solid rgba(0,0,0,0.09);
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-d); font-size: 1.5rem; color: var(--red);
    margin: 0 auto 22px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    transition: all 0.22s;
}
.step:hover .step-num { background: var(--red); color: white; border-color: var(--red); box-shadow: 0 8px 24px rgba(212,43,43,0.28); }
.step-title { font-size: 0.92rem; font-weight: 700; color: var(--ink); margin-bottom: 8px; }
.step-desc { font-size: 0.79rem; color: var(--muted); line-height: 1.7; }

/* ── ANIMATIONS ── */
@keyframes up {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes pulse-anim {
    0%,100% { opacity:1; transform:scale(1); }
    50% { opacity:0.45; transform:scale(1.35); }
}

/* ── RESPONSIVE ── */
@media (max-width: 960px) {
    .hero { grid-template-columns: 1fr; }
    .hero-right { display: none; }
    .hero-left { padding: 60px 28px 60px 28px; }
    .navbar { padding: 0 24px; }
    .stats { grid-template-columns: repeat(2,1fr); }
    .stat:nth-child(2) { border-right: none; }
    .cands-section, .pos-section, .how-section { padding: 60px 24px; }
    .steps { grid-template-columns: repeat(2,1fr); gap: 36px; }
    .steps::before { display: none; }
    .cands-grid { grid-template-columns: 1fr; }
}
@media (max-width: 560px) {
    .nav-links .nav-link:not(.nav-link-cta) { display: none; }
    .hero-h1 { font-size: 3.8rem; }
}
</style>

{{-- ══ NAVBAR ══ --}}
<nav class="navbar">
    <a href="{{ route('home') }}" class="nav-logo">UNI<span>VOTE</span></a>

    {{-- Desktop links --}}
    <div class="nav-links">
        <a href="#candidates" class="nav-link">Candidates</a>
        <a href="#positions" class="nav-link">Positions</a>
        <a href="#about" class="nav-link">How It Works</a>
        @auth
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}"
               class="nav-link nav-link-cta">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="nav-link nav-link-cta">Sign In</a>
        @endauth
    </div>

    {{-- Hamburger --}}
    <button class="hamburger" id="hamburger" aria-label="Toggle menu">
        <span></span><span></span><span></span>
    </button>
</nav>

{{-- Mobile dropdown --}}
<div class="mobile-menu" id="mobileMenu">
    <a href="#candidates" class="m-link" onclick="closeMenu()">Candidates</a>
    <a href="#positions"  class="m-link" onclick="closeMenu()">Positions</a>
    <a href="#about"      class="m-link" onclick="closeMenu()">How It Works</a>
    @auth
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}"
           class="m-link m-link-cta">Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="m-link m-link-cta">Sign In</a>
    @endauth
</div>

{{-- ══ HERO ══ --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>

    <div class="hero-left">
        <div class="hero-tag">
            @if(isset($activeElection) && $activeElection)
                <span class="dot-pulse"></span> Election Live · Vote Now
            @else
                University Voting System
            @endif
        </div>

        <h1 class="hero-h1">
            @if(isset($activeElection) && $activeElection)
                <span class="red">{{ strtoupper(explode(' ', $activeElection->title)[0]) }}</span>
                <span class="dim">{{ implode(' ', array_slice(explode(' ', $activeElection->title), 1)) }}</span>
            @else
                <span class="red">Your Voice</span>
                <span class="dim">Shapes Tomorrow</span>
            @endif
        </h1>

        <p class="hero-p">
            @if(isset($activeElection) && $activeElection)
                {{ $activeElection->description ?: 'Cast your vote securely for the positions below. Every vote shapes your campus future.' }}
                @if($activeElection->end_date)
                    Voting closes <strong>{{ \Carbon\Carbon::parse($activeElection->end_date)->format('M d, Y') }}</strong>.
                @endif
            @else
                Transparent, secure, and democratic campus elections. Every student's voice matters here.
            @endif
        </p>

        <div class="hero-btns">
            @auth
                @if(auth()->user()->isStudent() && isset($activeElection) && $activeElection)
                    <a href="{{ route('student.vote', $activeElection) }}" class="btn-p">
                        <i class="fa-solid fa-check-to-slot"></i> Cast Your Vote
                    </a>
                @endif
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}" class="btn-g">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-p">
                    <i class="fa-solid fa-right-to-bracket"></i> Sign In to Vote
                </a>
                <a href="#candidates" class="btn-g">View Candidates</a>
            @endauth
        </div>
    </div>

    {{-- Ballot preview widget --}}
    <div class="hero-right">
        <div class="ballot-widget">
            <div class="bw-head">
                <span class="bw-title">Live Ballot</span>
                <span class="bw-live"><span class="dot-pulse"></span> Active</span>
            </div>

            @if(isset($activeElection) && $activeElection && $activeElection->positions->count() > 0)
                @foreach($activeElection->positions->take(2) as $pos)
                    <div class="bw-pos-block">
                        <div class="bw-pos-label">{{ $pos->name }}</div>
                        @foreach($pos->candidates->take(2) as $ci => $cand)
                            <div class="bw-cand {{ $ci === 0 ? 'sel' : '' }}">
                                <div class="bw-av">{{ strtoupper(substr($cand->name, 0, 1)) }}</div>
                                <div>
                                    <div class="bw-nm">{{ $cand->name }}</div>
                                    <div class="bw-dp">{{ $pos->name }}</div>
                                </div>
                                <div class="bw-radio"></div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="bw-pos-block">
                    <div class="bw-pos-label">President</div>
                    <div class="bw-cand sel">
                        <div class="bw-av">J</div>
                        <div><div class="bw-nm">John Smith</div><div class="bw-dp">Sciences</div></div>
                        <div class="bw-radio"></div>
                    </div>
                    <div class="bw-cand">
                        <div class="bw-av">M</div>
                        <div><div class="bw-nm">Maria Garcia</div><div class="bw-dp">Arts</div></div>
                        <div class="bw-radio"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ══ STATS ══ --}}
<div class="stats">
    <div class="stat">
        <div class="stat-n">{{ $totalCandidates ?? 0 }}</div>
        <div class="stat-l">Candidates</div>
    </div>
    <div class="stat">
        <div class="stat-n">{{ $totalPositions ?? 0 }}</div>
        <div class="stat-l">Open Positions</div>
    </div>
    <div class="stat">
        <div class="stat-n">{{ $totalStudents ?? 0 }}</div>
        <div class="stat-l">Registered Voters</div>
    </div>
    <div class="stat">
        <div class="stat-n">{{ $totalVotes ?? 0 }}</div>
        <div class="stat-l">Votes Cast</div>
    </div>
</div>

{{-- ══ CANDIDATES ══ --}}
<section class="cands-section" id="candidates">
    <div class="sec-eyebrow">Meet the Candidates</div>
    <h2 class="sec-h2">Running for <em>Your</em> Campus</h2>

    @if(isset($activeElection) && $activeElection && $activeElection->positions->count() > 0)

        @php $hasAnyCandidates = false; @endphp
        @foreach($activeElection->positions as $position)
            @if($position->candidates->where('is_approved', true)->count() > 0)
                @php $hasAnyCandidates = true; @endphp
            @endif
        @endforeach

        @if($hasAnyCandidates)
            @foreach($activeElection->positions as $position)
                @php $approvedCandidates = $position->candidates->where('is_approved', true); @endphp
                @if($approvedCandidates->count() > 0)
                    <div class="pos-block" id="pos-{{ $position->id }}">
                        <div class="pos-row">
                            <span class="pos-name-tag">{{ $position->name }}</span>
                            <span class="pos-count-badge">{{ $approvedCandidates->count() }} {{ Str::plural('Candidate', $approvedCandidates->count()) }}</span>
                            <span class="pos-line"></span>
                        </div>
                        <div class="cands-grid">
                            @foreach($approvedCandidates as $candidate)
                                <div class="cand-card">
                                    <div class="cand-top">
                                        @if($candidate->photo)
                                            <img src="{{ Storage::url($candidate->photo) }}"
                                                 class="cand-av-img" alt="{{ $candidate->name }}">
                                        @else
                                            <div class="cand-av-init">
                                                {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="cand-pos-chip">{{ $position->name }}</div>
                                    </div>
                                    <div class="cand-body">
                                        <h4 class="cand-name">{{ $candidate->name }}</h4>
                                        <div class="cand-manifesto">
                                            @if($candidate->description)
                                                {{ $candidate->description }}
                                            @else
                                                No manifesto provided yet.
                                            @endif
                                        </div>
                                    </div>
                                    <div class="cand-foot">
                                        <span class="cand-pos-label">{{ $position->name }}</span>
                                        @auth
                                            @if(auth()->user()->isStudent())
                                                <a href="{{ route('student.vote', $activeElection) }}" class="cand-action">Vote</a>
                                            @elseif(auth()->user()->isAdmin())
                                                <a href="{{ route('admin.elections.positions.candidates.show', [$activeElection, $position, $candidate]) }}" class="cand-action">View</a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="cand-action">Sign In</a>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="no-data">
                <i class="fa-solid fa-users-slash"></i>
                <h3>No Approved Candidates</h3>
                <p>Candidates are pending approval. Check back soon.</p>
            </div>
        @endif

    @else
        <div class="no-data">
            <i class="fa-solid fa-calendar-xmark"></i>
            <h3>No Active Election</h3>
            <p>There is no active election at the moment. Check back later.</p>
        </div>
    @endif
</section>

{{-- ══ POSITIONS OVERVIEW ══ --}}
@if(isset($activeElection) && $activeElection && $activeElection->positions->count() > 0)
<section class="pos-section" id="positions">
    <div class="sec-eyebrow">Open Positions</div>
    <h2 class="sec-h2">What You're <em>Voting</em> For</h2>
    <div class="pos-cards">
        @foreach($activeElection->positions as $position)
            @if($position->candidates->where('is_approved', true)->count() > 0)
                <a href="#pos-{{ $position->id }}" class="pos-card">
                    <div class="pos-card-icon"><i class="fa-solid fa-user-tie"></i></div>
                    <div class="pos-card-name">{{ $position->name }}</div>
                    <div class="pos-card-meta">{{ $position->candidates->count() }} {{ Str::plural('Candidate', $position->candidates->count()) }}</div>
                    <div class="pos-chips">
                        @foreach($position->candidates->take(3) as $c)
                            <span class="pos-chip">{{ Str::limit($c->name, 14) }}</span>
                        @endforeach
                        @if($position->candidates->count() > 3)
                            <span class="pos-chip">+{{ $position->candidates->count() - 3 }} more</span>
                        @endif
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</section>
@endif

{{-- ══ HOW IT WORKS ══ --}}
<section class="how-section" id="about">
    <div class="sec-eyebrow">Process</div>
    <h2 class="sec-h2">How It <em>Works</em></h2>
    <div class="steps">
        <div class="step">
            <div class="step-num">01</div>
            <div class="step-title">Sign In</div>
            <p class="step-desc">Log in with your university credentials to access the secure voting portal.</p>
        </div>
        <div class="step">
            <div class="step-num">02</div>
            <div class="step-title">Review Candidates</div>
            <p class="step-desc">Browse profiles and manifestos. Make an informed decision for each position.</p>
        </div>
        <div class="step">
            <div class="step-num">03</div>
            <div class="step-title">Cast Your Vote</div>
            <p class="step-desc">Select one candidate per position and submit your ballot securely.</p>
        </div>
        <div class="step">
            <div class="step-num">04</div>
            <div class="step-title">See Results</div>
            <p class="step-desc">Results are published transparently once the election period closes.</p>
        </div>
    </div>
</section>

<script>
    const hamburger  = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('open');
        mobileMenu.classList.toggle('open');
    });

    function closeMenu() {
        hamburger.classList.remove('open');
        mobileMenu.classList.remove('open');
    }

    document.addEventListener('click', (e) => {
        if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
            closeMenu();
        }
    });
</script>

@endsection
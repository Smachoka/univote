@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div style="max-width:780px;margin:0 auto;">

    {{-- Page header --}}
    <div style="margin-bottom:24px;">
        <h2 style="font-size:1.1rem;font-weight:700;color:#333;margin:0 0 4px;">System Settings</h2>
        <p style="font-size:0.82rem;color:#888;margin:0;">Configure mail and system options for UniVote.</p>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;border-radius:7px;padding:11px 16px;margin-bottom:20px;font-size:0.85rem;display:flex;align-items:center;gap:8px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;border-radius:7px;padding:11px 16px;margin-bottom:20px;font-size:0.85rem;display:flex;align-items:center;gap:8px;">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    {{-- ── MAIL SETTINGS CARD ── --}}
    <div style="background:white;border-radius:10px;border:1px solid #e8e8e8;box-shadow:0 2px 8px rgba(0,0,0,0.05);margin-bottom:24px;overflow:hidden;">

        {{-- Card header --}}
        <div style="padding:16px 22px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;gap:10px;background:#fafafa;">
            <div style="width:34px;height:34px;background:#fff0f0;border-radius:7px;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-envelope" style="color:#c0392b;font-size:0.9rem;"></i>
            </div>
            <div>
                <h3 style="font-size:0.95rem;font-weight:700;color:#111;margin:0;">Mail Configuration</h3>
                <p style="font-size:0.75rem;color:#999;margin:0;">SMTP settings for sending student credentials</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" style="padding:22px;">
            @csrf
            @method('PUT')

            {{-- Mailer type --}}
            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:0.75rem;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                    Mail Driver
                </label>
                <select name="mail_mailer" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:0.88rem;color:#333;background:white;outline:none;" onchange="toggleSmtp(this.value)">
                    <option value="smtp"    {{ $settings['mail_mailer'] === 'smtp'    ? 'selected' : '' }}>SMTP (Gmail, Mailgun, custom)</option>
                    <option value="log"     {{ $settings['mail_mailer'] === 'log'     ? 'selected' : '' }}>Log (development only — writes to laravel.log)</option>
                    <option value="mailgun" {{ $settings['mail_mailer'] === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                    <option value="ses"     {{ $settings['mail_mailer'] === 'ses'     ? 'selected' : '' }}>Amazon SES</option>
                </select>
            </div>

            {{-- SMTP fields --}}
            <div id="smtpFields" style="{{ $settings['mail_mailer'] === 'log' ? 'display:none;' : '' }}">

                {{-- Host + Port --}}
                <div style="display:grid;grid-template-columns:1fr 120px;gap:12px;margin-bottom:18px;">
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                            SMTP Host
                        </label>
                        <input type="text" name="mail_host"
                               value="{{ $settings['mail_host'] }}"
                               placeholder="smtp.gmail.com"
                               style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:0.88rem;color:#333;box-sizing:border-box;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                            Port
                        </label>
                        <input type="number" name="mail_port"
                               value="{{ $settings['mail_port'] }}"
                               placeholder="587"
                               style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:0.88rem;color:#333;box-sizing:border-box;outline:none;">
                    </div>
                </div>

                {{-- Username + Password --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px;">
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                            Username / Email
                        </label>
                        <input type="text" name="mail_username"
                               value="{{ $settings['mail_username'] }}"
                               placeholder="your@gmail.com"
                               style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:0.88rem;color:#333;box-sizing:border-box;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                            Password / App Password
                        </label>
                        <div style="position:relative;">
                            <input type="password" name="mail_password" id="mailPassword"
                                   value="{{ $settings['mail_password'] }}"
                                   placeholder="••••••••••••"
                                   style="width:100%;padding:10px 36px 10px 12px;border:1px solid #ddd;border-radius:6px;font-size:0.88rem;color:#333;box-sizing:border-box;outline:none;">
                            <button type="button" onclick="togglePw()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#aaa;font-size:0.85rem;">
                                <i class="fa-solid fa-eye" id="pwIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Encryption --}}
                <div style="margin-bottom:18px;">
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                        Encryption
                    </label>
                    <div style="display:flex;gap:12px;">
                        @foreach(['tls' => 'TLS (recommended)', 'ssl' => 'SSL', '' => 'None'] as $val => $label)
                            <label style="display:flex;align-items:center;gap:6px;font-size:0.85rem;color:#555;cursor:pointer;">
                                <input type="radio" name="mail_encryption" value="{{ $val }}"
                                       {{ $settings['mail_encryption'] === $val ? 'checked' : '' }}
                                       style="accent-color:#c0392b;">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- From address + name --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:22px;padding-top:4px;border-top:1px solid #f5f5f5;">
                <div style="padding-top:16px;">
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                        From Address
                    </label>
                    <input type="email" name="mail_from_address"
                           value="{{ $settings['mail_from_address'] }}"
                           placeholder="noreply@university.edu"
                           style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:0.88rem;color:#333;box-sizing:border-box;outline:none;">
                </div>
                <div style="padding-top:16px;">
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                        From Name
                    </label>
                    <input type="text" name="mail_from_name"
                           value="{{ $settings['mail_from_name'] }}"
                           placeholder="UniVote"
                           style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:0.88rem;color:#333;box-sizing:border-box;outline:none;">
                </div>
            </div>

            {{-- Gmail help tip --}}
            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:7px;padding:12px 14px;margin-bottom:20px;font-size:0.8rem;color:#92400e;display:flex;gap:10px;">
                <i class="fa-solid fa-lightbulb" style="margin-top:1px;flex-shrink:0;"></i>
                <div>
                    <strong>Using Gmail?</strong> Go to your Google Account →
                    Security → 2-Step Verification → App Passwords.
                    Generate an app password and use it here instead of your regular password.
                    Host: <code>smtp.gmail.com</code> · Port: <code>587</code> · Encryption: <code>TLS</code>
                </div>
            </div>

            <button type="submit" style="background:#c0392b;color:white;padding:11px 28px;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:background 0.2s;"
                    onmouseover="this.style.background='#a93226'" onmouseout="this.style.background='#c0392b'">
                <i class="fa-solid fa-floppy-disk"></i> Save Settings
            </button>
        </form>
    </div>

    {{-- ── TEST MAIL CARD ── --}}
    <div style="background:white;border-radius:10px;border:1px solid #e8e8e8;box-shadow:0 2px 8px rgba(0,0,0,0.05);overflow:hidden;">
        <div style="padding:16px 22px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;gap:10px;background:#fafafa;">
            <div style="width:34px;height:34px;background:#f0fdf4;border-radius:7px;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-paper-plane" style="color:#166534;font-size:0.9rem;"></i>
            </div>
            <div>
                <h3 style="font-size:0.95rem;font-weight:700;color:#111;margin:0;">Send Test Email</h3>
                <p style="font-size:0.75rem;color:#999;margin:0;">Verify your mail configuration is working</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.settings.test-mail') }}" style="padding:22px;display:flex;gap:12px;align-items:flex-end;">
            @csrf
            <div style="flex:1;">
                <label style="display:block;font-size:0.75rem;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
                    Send test to
                </label>
                <input type="email" name="test_email"
                       placeholder="your@email.com"
                       value="{{ auth()->user()->email }}"
                       style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:0.88rem;color:#333;box-sizing:border-box;outline:none;">
            </div>
            <button type="submit" style="background:#166534;color:white;padding:11px 22px;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:7px;white-space:nowrap;"
                    onmouseover="this.style.background='#14532d'" onmouseout="this.style.background='#166534'">
                <i class="fa-solid fa-paper-plane"></i> Send Test
            </button>
        </form>
    </div>

</div>

<script>
function toggleSmtp(val) {
    document.getElementById('smtpFields').style.display = val === 'log' ? 'none' : '';
}
function togglePw() {
    const input = document.getElementById('mailPassword');
    const icon  = document.getElementById('pwIcon');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash';
}
</script>
@endsection
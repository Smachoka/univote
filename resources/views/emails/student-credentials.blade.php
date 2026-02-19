<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 560px; margin: 40px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: #d42b2b; padding: 32px 40px; text-align: center; }
        .header h1 { font-size: 1.6rem; color: white; margin: 0; letter-spacing: 0.06em; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.75); margin: 6px 0 0; font-size: 0.88rem; }
        .body { padding: 36px 40px; }
        .body p { color: #444; font-size: 0.95rem; line-height: 1.7; }
        .creds { background: #f8f8f8; border: 1px solid #eee; border-left: 4px solid #d42b2b; border-radius: 6px; padding: 20px 24px; margin: 24px 0; }
        .cred-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        .cred-row:last-child { border-bottom: none; }
        .cred-label { color: #999; font-weight: 600; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.06em; }
        .cred-value { color: #111; font-weight: 600; font-family: monospace; font-size: 0.95rem; }
        .btn { display: block; background: #d42b2b; color: white; text-decoration: none; text-align: center; padding: 14px 28px; border-radius: 6px; font-weight: 700; font-size: 0.92rem; margin: 24px 0 0; }
        .footer { background: #f8f8f8; padding: 20px 40px; text-align: center; font-size: 0.78rem; color: #aaa; border-top: 1px solid #eee; }
        .warning { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 6px; padding: 12px 16px; font-size: 0.82rem; color: #92400e; margin-top: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>UNIVOTE</h1>
        <p>University Voting System — Login Credentials</p>
    </div>
    <div class="body">
        <p>Hello <strong>{{ $studentName }}</strong>,</p>
        <p>Your student account has been created on the UniVote platform. Use the credentials below to sign in and participate in campus elections.</p>

        <div class="creds">
            <div class="cred-row">
                <span class="cred-label">Student ID</span>
                <span class="cred-value">{{ $studentId }}</span>
            </div>
            <div class="cred-row">
                <span class="cred-label">Email</span>
                <span class="cred-value">{{ $email }}</span>
            </div>
            <div class="cred-row">
                <span class="cred-label">Password</span>
                <span class="cred-value">{{ $plainPassword }}</span>
            </div>
        </div>

        <a href="{{ url('/login') }}" class="btn">Sign In to UniVote →</a>

        <div class="warning">
            ⚠️ Please change your password after your first login. Keep these credentials private and do not share them.
        </div>
    </div>
    <div class="footer">
        © {{ date('Y') }} UniVote · University Voting System<br>
        This is an automated message. Do not reply to this email.
    </div>
</div>
</body>
</html>
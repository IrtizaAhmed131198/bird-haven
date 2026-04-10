<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Reset Your Password | Bird Haven</title>
    <style>
        body { margin:0; padding:0; background:#f4f9fc; font-family:'Helvetica Neue', Arial, sans-serif; }
        .wrapper { max-width:540px; margin:40px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
        .header { background:#004f64; padding:28px 40px; }
        .header h1 { color:#fff; font-size:20px; margin:0; }
        .header p { color:#7dd3ee; font-size:12px; margin:4px 0 0; }
        .body { padding:36px 40px; }
        .cta-btn { display:inline-block; background:#0c6780; color:#fff; text-decoration:none; padding:14px 32px; border-radius:50px; font-weight:700; font-size:15px; margin:24px 0; }
        .url-box { background:#f4f9fc; border-radius:8px; padding:12px 16px; font-size:12px; color:#374151; word-break:break-all; line-height:1.6; }
        .footer { border-top:1px solid #e5e7eb; padding:20px 40px; font-size:11px; color:#9ca3af; text-align:center; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Bird Haven</h1>
            <p>Password Reset Request</p>
        </div>
        <div class="body">
            <p style="font-size:15px; color:#1a1c1e; margin-bottom:8px;">Hello {{ $notifiable->name }},</p>
            <p style="font-size:14px; color:#374151; line-height:1.7;">
                We received a request to reset your Bird Haven password. Click the button below to set a new one.
                This link expires in <strong>60 minutes</strong>.
            </p>
            <div style="text-align:center;">
                <a href="{{ $actionUrl }}" class="cta-btn">Reset My Password</a>
            </div>
            <p style="font-size:13px; color:#6b7280; margin-bottom:8px;">Or copy this link into your browser:</p>
            <div class="url-box">{{ $actionUrl }}</div>
            <p style="font-size:13px; color:#9ca3af; margin-top:24px; line-height:1.7;">
                If you did not request a password reset, you can safely ignore this email — your password will remain unchanged.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bird Haven &mdash; Ethical Avian Guardianship
        </div>
    </div>
</body>
</html>

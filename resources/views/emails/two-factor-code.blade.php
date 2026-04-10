<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Your Login Code | Bird Haven</title>
    <style>
        body { margin:0; padding:0; background:#f4f9fc; font-family: 'Helvetica Neue', Arial, sans-serif; }
        .wrapper { max-width:520px; margin:40px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
        .header { background:#004f64; padding:32px 40px; text-align:center; }
        .header h1 { color:#ffffff; font-size:22px; margin:0; letter-spacing:-0.5px; }
        .header p { color:#7dd3ee; font-size:12px; margin:6px 0 0; letter-spacing:0.1em; text-transform:uppercase; }
        .body { padding:40px; text-align:center; }
        .greeting { font-size:16px; color:#374151; margin-bottom:8px; }
        .instruction { font-size:14px; color:#6b7280; margin-bottom:32px; line-height:1.6; }
        .code-box { display:inline-block; background:#f0faff; border:2px dashed #0c6780; border-radius:12px; padding:20px 48px; margin-bottom:32px; }
        .code { font-size:42px; font-weight:900; letter-spacing:12px; color:#004f64; font-family: 'Courier New', monospace; }
        .expiry { font-size:12px; color:#9ca3af; margin-bottom:32px; }
        .expiry strong { color:#ef4444; }
        .warning { background:#fef3c7; border-radius:10px; padding:14px 20px; font-size:12px; color:#92400e; margin-bottom:24px; text-align:left; }
        .footer { border-top:1px solid #e5e7eb; padding:24px 40px; text-align:center; font-size:11px; color:#9ca3af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Bird Haven</h1>
            <p>Two-Factor Authentication</p>
        </div>
        <div class="body">
            <p class="greeting">Hello, <strong>{{ $user->name }}</strong></p>
            <p class="instruction">
                Use the code below to complete your login.<br/>
                Do not share this code with anyone.
            </p>
            <div class="code-box">
                <div class="code">{{ $user->two_factor_code }}</div>
            </div>
            <p class="expiry">This code expires in <strong>5 minutes</strong></p>
            <div class="warning">
                If you did not attempt to log in to Bird Haven, please ignore this email and consider changing your password immediately.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bird Haven &mdash; All rights reserved.
        </div>
    </div>
</body>
</html>

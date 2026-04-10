<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>New Contact Message | Bird Haven</title>
    <style>
        body { margin:0; padding:0; background:#f4f9fc; font-family:'Helvetica Neue', Arial, sans-serif; }
        .wrapper { max-width:540px; margin:40px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
        .header { background:#004f64; padding:28px 40px; }
        .header h1 { color:#fff; font-size:20px; margin:0; }
        .header p { color:#7dd3ee; font-size:12px; margin:4px 0 0; }
        .body { padding:36px 40px; }
        .row { margin-bottom:20px; }
        .label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#6b7280; margin-bottom:4px; }
        .value { font-size:14px; color:#1a1c1e; }
        .message-box { background:#f4f9fc; border-left:4px solid #0c6780; border-radius:0 8px 8px 0; padding:16px 20px; font-size:14px; color:#374151; line-height:1.7; }
        .footer { border-top:1px solid #e5e7eb; padding:20px 40px; font-size:11px; color:#9ca3af; text-align:center; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Bird Haven</h1>
            <p>New contact form message</p>
        </div>
        <div class="body">
            @if(! empty($data['name']))
                <div class="row">
                    <div class="label">Name</div>
                    <div class="value">{{ $data['name'] }}</div>
                </div>
            @endif
            <div class="row">
                <div class="label">Email</div>
                <div class="value"><a href="mailto:{{ $data['email'] }}" style="color:#0c6780;">{{ $data['email'] }}</a></div>
            </div>
            @if(! empty($data['subject']))
                <div class="row">
                    <div class="label">Subject</div>
                    <div class="value">{{ $data['subject'] }}</div>
                </div>
            @endif
            <div class="row">
                <div class="label">Message</div>
                <div class="message-box">{{ $data['message'] }}</div>
            </div>
            <div class="row" style="margin-bottom:0;">
                <div class="label">Received at</div>
                <div class="value">{{ now()->format('d M Y, h:i A') }}</div>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bird Haven &mdash; This message was sent via the contact form.
        </div>
    </div>
</body>
</html>

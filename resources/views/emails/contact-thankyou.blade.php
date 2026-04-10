<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Thank You | Bird Haven</title>
    <style>
        body { margin:0; padding:0; background:#f4f9fc; font-family:'Helvetica Neue', Arial, sans-serif; }
        .wrapper { max-width:540px; margin:40px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
        .header { background:#004f64; padding:28px 40px; }
        .header h1 { color:#fff; font-size:20px; margin:0; }
        .header p { color:#7dd3ee; font-size:12px; margin:4px 0 0; }
        .hero { background:linear-gradient(135deg,#e8f7fc 0%,#f0fbff 100%); padding:32px 40px; text-align:center; border-bottom:1px solid #e5e7eb; }
        .hero .icon { font-size:48px; margin-bottom:12px; }
        .hero h2 { font-size:22px; font-weight:800; color:#004f64; margin:0 0 8px; }
        .hero p { font-size:14px; color:#374151; margin:0; line-height:1.6; }
        .body { padding:32px 40px; }
        .message-recap { background:#f4f9fc; border-left:4px solid #0c6780; border-radius:0 8px 8px 0; padding:16px 20px; font-size:13px; color:#374151; line-height:1.7; margin:20px 0; }
        .row { margin-bottom:16px; }
        .label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#6b7280; margin-bottom:3px; }
        .value { font-size:13px; color:#1a1c1e; }
        .cta-area { text-align:center; margin:28px 0 8px; }
        .cta-btn { display:inline-block; background:#0c6780; color:#fff; text-decoration:none; padding:12px 28px; border-radius:50px; font-weight:700; font-size:14px; }
        .footer { border-top:1px solid #e5e7eb; padding:20px 40px; font-size:11px; color:#9ca3af; text-align:center; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Bird Haven</h1>
            <p>We got your message</p>
        </div>

        <div class="hero">
            <div class="icon">🦜</div>
            <h2>Thank you, {{ $contactMessage->name }}!</h2>
            <p>Your message has been received. Our team will get back to you<br>within <strong>24 hours</strong>.</p>
        </div>

        <div class="body">
            <p style="font-size:14px; color:#374151; line-height:1.7; margin-bottom:20px;">
                Here's a copy of what you sent us:
            </p>

            @if ($contactMessage->topic)
            <div class="row">
                <div class="label">Topic</div>
                <div class="value">{{ $contactMessage->topic }}</div>
            </div>
            @endif

            @if ($contactMessage->subject)
            <div class="row">
                <div class="label">Subject</div>
                <div class="value">{{ $contactMessage->subject }}</div>
            </div>
            @endif

            <div class="row">
                <div class="label">Your Message</div>
                <div class="message-recap">{{ $contactMessage->message }}</div>
            </div>

            <p style="font-size:13px; color:#6b7280; line-height:1.7;">
                While you wait, feel free to explore our species database or browse our latest arrivals.
            </p>

            <div class="cta-area">
                <a href="{{ route('shop') }}" class="cta-btn">Explore Bird Haven</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Bird Haven &mdash; Ethical Avian Guardianship<br>
            If you did not submit this form, please disregard this email.
        </div>
    </div>
</body>
</html>

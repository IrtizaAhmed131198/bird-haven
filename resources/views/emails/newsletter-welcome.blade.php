<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to the Bird Haven Journal</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
</head>
<body style="background:#f8f9fa;margin:0;padding:0;font-family:'Plus Jakarta Sans',sans-serif;-webkit-font-smoothing:antialiased;">
<div style="max-width:640px;margin:0 auto;padding:32px 16px;">

    {{-- Header --}}
    <div style="background:#ffffff;border-radius:16px 16px 0 0;padding:28px 40px;display:flex;justify-content:space-between;align-items:center;margin-bottom:2px;">
        <span style="font-family:'Manrope',sans-serif;font-size:20px;font-weight:800;color:#0f172a;">Bird Haven</span>
        <span style="font-size:12px;font-weight:700;color:#00658b;text-transform:uppercase;letter-spacing:0.12em;">The Journal</span>
    </div>

    {{-- Hero --}}
    <div style="background:linear-gradient(135deg,#00658b 0%,#004f6b 100%);padding:60px 40px;text-align:center;">
        <div style="font-size:64px;line-height:1;margin-bottom:20px;">🦜</div>
        <h1 style="font-family:'Manrope',sans-serif;color:#ffffff;font-size:36px;font-weight:800;margin:0 0 16px;line-height:1.15;letter-spacing:-0.02em;">
            Welcome to the Flock
        </h1>
        <p style="color:#bfecfe;font-size:16px;line-height:1.7;margin:0;">
            You're now part of a community of ethical avian guardians.<br>
            Expect weekly insights straight to your inbox.
        </p>
    </div>

    {{-- Body --}}
    <div style="background:#ffffff;padding:48px 40px;">
        <p style="color:#3f484c;font-size:16px;line-height:1.75;margin:0 0 28px;">
            Hello{{ $subscriber->name ? ', ' . $subscriber->name : '' }},
        </p>
        <p style="color:#3f484c;font-size:16px;line-height:1.75;margin:0 0 28px;">
            Thank you for subscribing to the <strong style="color:#0f172a;">Bird Haven Avian Care Journal</strong>.
            Each week we'll bring you expert care tips, species spotlights, sanctuary updates, and exclusive offers — curated just for guardians who care.
        </p>

        {{-- What to expect cards --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:36px;">
            <div style="background:#f0f9ff;border-radius:12px;padding:24px;">
                <div style="font-size:28px;margin-bottom:12px;">🌿</div>
                <h3 style="font-family:'Manrope',sans-serif;font-size:14px;font-weight:700;color:#0f172a;margin:0 0 6px;">Care Guides</h3>
                <p style="color:#64748b;font-size:13px;line-height:1.6;margin:0;">Species-specific nutrition, habitat, and enrichment tips.</p>
            </div>
            <div style="background:#f0fdf4;border-radius:12px;padding:24px;">
                <div style="font-size:28px;margin-bottom:12px;">🐦</div>
                <h3 style="font-family:'Manrope',sans-serif;font-size:14px;font-weight:700;color:#0f172a;margin:0 0 6px;">Species Spotlights</h3>
                <p style="color:#64748b;font-size:13px;line-height:1.6;margin:0;">Deep dives into rare and fascinating avian species.</p>
            </div>
            <div style="background:#fefce8;border-radius:12px;padding:24px;">
                <div style="font-size:28px;margin-bottom:12px;">🏷️</div>
                <h3 style="font-family:'Manrope',sans-serif;font-size:14px;font-weight:700;color:#0f172a;margin:0 0 6px;">Exclusive Offers</h3>
                <p style="color:#64748b;font-size:13px;line-height:1.6;margin:0;">Subscriber-only discounts and early access to new arrivals.</p>
            </div>
            <div style="background:#fdf4ff;border-radius:12px;padding:24px;">
                <div style="font-size:28px;margin-bottom:12px;">🌍</div>
                <h3 style="font-family:'Manrope',sans-serif;font-size:14px;font-weight:700;color:#0f172a;margin:0 0 6px;">Conservation News</h3>
                <p style="color:#64748b;font-size:13px;line-height:1.6;margin:0;">Updates on our conservation contributions and wildlife efforts.</p>
            </div>
        </div>

        {{-- CTA --}}
        <div style="text-align:center;margin-bottom:36px;">
            <a href="{{ route('shop') }}"
                style="display:inline-block;background:linear-gradient(135deg,#00658b,#0085b3);color:#ffffff;padding:18px 48px;border-radius:9999px;font-weight:700;font-size:16px;text-decoration:none;box-shadow:0 10px 30px rgba(0,101,139,0.25);">
                Explore the Sanctuary →
            </a>
        </div>

        <p style="color:#94a3b8;font-size:13px;line-height:1.6;text-align:center;margin:0;">
            You subscribed with <strong>{{ $subscriber->email }}</strong>.<br>
            If this was a mistake, simply ignore this email — no action needed.
        </p>
    </div>

    {{-- Footer --}}
    <div style="background:#f8fafc;border-radius:0 0 16px 16px;padding:28px 40px;border-top:1px solid #e2e8f0;text-align:center;">
        <p style="color:#94a3b8;font-size:11px;text-transform:uppercase;letter-spacing:0.12em;margin:0;">
            © {{ date('Y') }} Bird Haven · Ethical Avian Guardianship
        </p>
    </div>

</div>
</body>
</html>

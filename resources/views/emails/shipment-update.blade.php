<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Shipment Update | Bird Haven</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #f8f9fa; color: #191c1d; font-family: 'Plus Jakarta Sans', sans-serif; -webkit-font-smoothing: antialiased; margin: 0; padding: 0; }
        h1, h2, h3, h4 { font-family: 'Manrope', sans-serif; }
        .gradient-primary { background: linear-gradient(135deg, #00658b 0%, #77cefe 100%); }
        .bg-glass { background: rgba(255,255,255,0.8); backdrop-filter: blur(20px); }
    </style>
</head>
<body style="background-color:#f8f9fa;">
    <div style="max-width:680px;margin:0 auto;padding:32px 16px;">

        {{-- Header --}}
        <div style="display:flex;justify-content:space-between;align-items:center;padding:24px 32px;background:#ffffff;border-radius:16px 16px 0 0;margin-bottom:2px;">
            <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin:0;letter-spacing:-0.02em;">Bird Haven</h2>
            <span style="color:#00658b;font-weight:700;font-size:13px;">ORDER #{{ $order->order_number }}</span>
        </div>

        {{-- Hero Banner --}}
        <div style="position:relative;width:100%;height:220px;overflow:hidden;background:linear-gradient(135deg,#00658b 0%,#77cefe 100%);">
            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                <span style="font-size:80px;line-height:1;">🐦</span>
            </div>
            <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,0.6),transparent);display:flex;flex-direction:column;justify-content:flex-end;padding:40px;">
                <span style="color:#77cefe;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;font-size:12px;margin-bottom:12px;">Update Tracker</span>
                <h1 style="color:#ffffff;font-size:40px;font-weight:800;margin:0 0 12px;line-height:1.1;letter-spacing:-0.02em;">On the Wings of Flight</h1>
                <p style="color:#e2e8f0;font-size:16px;margin:0;line-height:1.6;">Your avian journey has officially begun. We are ensuring a gentle transit for your new companion.</p>
            </div>
        </div>

        {{-- Main Content --}}
        <div style="background:#ffffff;padding:48px 40px;">
            <h2 style="color:#00658b;font-size:28px;font-weight:700;margin:0 0 16px;letter-spacing:-0.01em;">Great news, {{ $user->first_name ?? $user->name }}! Your companion is now in transit!</h2>
            <p style="color:#3f484c;font-size:16px;line-height:1.7;margin:0 0 32px;">
                We are thrilled to inform you that the final health check has been passed with flying colors. Your bird is currently on a climate-controlled, ethically-monitored flight path to its new sanctuary—your home.
            </p>

            {{-- Status Cards --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:32px;">
                <div style="background:#f3f4f5;padding:28px;border-radius:12px;">
                    <div style="color:#00658b;font-size:28px;margin-bottom:16px;">📦</div>
                    <p style="color:#6f787d;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin:0 0 6px;">Tracking ID</p>
                    <p style="color:#191c1d;font-size:18px;font-weight:700;margin:0;">{{ $shipment->tracking_number ?? 'BH-8849-FLY-NAV' }}</p>
                </div>
                <div style="background:#cfecb4;padding:28px;border-radius:12px;">
                    <div style="color:#4d653a;font-size:28px;margin-bottom:16px;">📅</div>
                    <p style="color:#536b3f;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin:0 0 6px;">Estimated Delivery</p>
                    <p style="color:#191c1d;font-size:18px;font-weight:700;margin:0;">{{ $shipment->estimated_delivery ?? 'In 5-7 Business Days' }}</p>
                </div>
            </div>

            {{-- CTA Button --}}
            <div style="text-align:center;margin-bottom:40px;">
                <a href="{{ route('shipping.tracking') }}"
                    style="display:inline-flex;align-items:center;background:linear-gradient(135deg,#00658b,#77cefe);color:#ffffff;padding:18px 40px;border-radius:9999px;font-weight:700;font-size:16px;text-decoration:none;box-shadow:0 10px 30px rgba(0,101,139,0.25);">
                    Track My Companion →
                </a>
            </div>

            {{-- Care Tips --}}
            <div style="border-top:1px solid #bfc8cd;padding-top:40px;">
                <h3 style="font-size:24px;font-weight:700;margin:0 0 8px;">Preparing Your Home</h3>
                <p style="color:#3f484c;font-size:15px;margin:0 0 28px;line-height:1.6;">Ensure a soft landing for your new ward with these essential guardian preparations.</p>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;">
                    <div>
                        <h4 style="font-size:15px;font-weight:700;margin:0 0 8px;">Quiet Acclimation</h4>
                        <p style="color:#3f484c;font-size:13px;line-height:1.6;margin:0;">Position the sanctuary in a low-traffic area for the first 48 hours.</p>
                    </div>
                    <div>
                        <h4 style="font-size:15px;font-weight:700;margin:0 0 8px;">Nutritional Welcome</h4>
                        <p style="color:#3f484c;font-size:13px;line-height:1.6;margin:0;">Prepare fresh water and a curated seed-and-pellet mix as recommended.</p>
                    </div>
                    <div>
                        <h4 style="font-size:15px;font-weight:700;margin:0 0 8px;">Sanctuary Warmth</h4>
                        <p style="color:#3f484c;font-size:13px;line-height:1.6;margin:0;">Maintain 70-80°F ambient temperature to reduce arrival stress.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div style="background:#f8fafc;padding:32px 40px;border-radius:0 0 16px 16px;border-top:1px solid #e2e8f0;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <strong style="color:#0f172a;font-size:16px;">Bird Haven</strong>
                <div style="display:flex;gap:16px;">
                    <a href="{{ route('contact') }}" style="color:#94a3b8;font-size:12px;text-decoration:none;">Contact</a>
                    <a href="#" style="color:#94a3b8;font-size:12px;text-decoration:none;">Privacy Policy</a>
                    <a href="#" style="color:#94a3b8;font-size:12px;text-decoration:none;">Ethical Sourcing</a>
                </div>
            </div>
            <p style="color:#94a3b8;font-size:11px;text-align:center;margin:0;text-transform:uppercase;letter-spacing:0.15em;">
                © {{ date('Y') }} Bird Haven. Ethical Avian Guardianship.
            </p>
        </div>
    </div>
</body>
</html>

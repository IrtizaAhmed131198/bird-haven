<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Order Confirmed | Bird Haven</title>
    <style>
        body { margin:0; padding:0; background:#f4f9fc; font-family:'Helvetica Neue', Arial, sans-serif; }
        .wrapper { max-width:560px; margin:40px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.07); }
        .header { background:#004f64; padding:28px 40px; }
        .header h1 { color:#fff; font-size:22px; margin:0 0 4px; }
        .header p { color:#7dd3ee; font-size:13px; margin:0; }
        .hero { background:#e8f7fc; padding:24px 40px; border-bottom:1px solid #e5e7eb; }
        .hero .order-num { font-size:13px; color:#0c6780; font-weight:700; text-transform:uppercase; letter-spacing:.08em; }
        .hero .amount { font-size:28px; font-weight:800; color:#004f64; margin:4px 0 0; }
        .body { padding:32px 40px; }
        .section-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#6b7280; margin-bottom:12px; }
        .item-row { display:flex; align-items:center; gap:14px; margin-bottom:12px; }
        .item-img { width:48px; height:48px; border-radius:8px; object-fit:cover; background:#f4f9fc; }
        .item-name { font-size:14px; font-weight:600; color:#1a1c1e; }
        .item-meta { font-size:12px; color:#6b7280; }
        .item-price { margin-left:auto; font-size:14px; font-weight:700; color:#1a1c1e; white-space:nowrap; }
        .divider { border:none; border-top:1px solid #e5e7eb; margin:20px 0; }
        .total-row { display:flex; justify-content:space-between; font-size:13px; color:#374151; margin-bottom:6px; }
        .total-row.grand { font-size:16px; font-weight:800; color:#004f64; }
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-top:20px; }
        .info-block .lbl { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#6b7280; margin-bottom:4px; }
        .info-block .val { font-size:13px; color:#374151; line-height:1.5; }
        .cta-btn { display:inline-block; background:#0c6780; color:#fff; text-decoration:none; padding:12px 28px; border-radius:50px; font-weight:700; font-size:14px; margin-top:24px; }
        .footer { border-top:1px solid #e5e7eb; padding:20px 40px; font-size:11px; color:#9ca3af; text-align:center; }
        .badge { display:inline-block; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; }
        .badge-cod { background:#fef9c3; color:#854d0e; }
        .badge-jazz { background:#fee2e2; color:#991b1b; }
        .badge-bank { background:#dbeafe; color:#1e40af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Bird Haven</h1>
            <p>Your order has been confirmed</p>
        </div>

        <div class="hero">
            <div class="order-num">Order #{{ $order->order_number }}</div>
            <div class="amount">${{ number_format($order->total, 2) }}</div>
            <p style="font-size:13px; color:#374151; margin:6px 0 0;">
                Thank you, <strong>{{ $order->user?->name ?? $order->shipping_name }}</strong>!
                We've received your order and it's now being prepared.
            </p>
        </div>

        <div class="body">

            {{-- Items --}}
            <div class="section-title">Items Ordered</div>
            @foreach ($order->items as $item)
            @php
                $product = $item->bird ?? $item->accessory;
                $imgSrc  = $product?->image
                    ? asset('uploads/images/' . ($item->bird_id ? 'birds' : 'accessories') . '/' . $product->image)
                    : '';
            @endphp
            <div class="item-row">
                @if ($imgSrc)
                    <img src="{{ $imgSrc }}" class="item-img" alt="">
                @else
                    <div class="item-img" style="background:#e8f7fc;"></div>
                @endif
                <div>
                    <div class="item-name">{{ $product?->name ?? 'Product' }}</div>
                    <div class="item-meta">Qty: {{ $item->quantity }}</div>
                </div>
                <div class="item-price">${{ number_format($item->price * $item->quantity, 2) }}</div>
            </div>
            @endforeach

            <hr class="divider">

            <div class="total-row"><span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
            <div class="total-row"><span>Shipping</span><span>{{ $order->shipping > 0 ? '$'.number_format($order->shipping,2) : 'Free' }}</span></div>
            <div class="total-row grand"><span>Total</span><span>${{ number_format($order->total, 2) }}</span></div>

            <hr class="divider">

            {{-- Shipping + Payment --}}
            <div class="info-grid">
                <div class="info-block">
                    <div class="lbl">Shipping To</div>
                    <div class="val">
                        {{ $order->shipping_name }}<br>
                        {{ $order->shipping_address }}
                        @if ($order->shipping_address2)<br>{{ $order->shipping_address2 }}@endif<br>
                        {{ $order->shipping_city }} {{ $order->shipping_postal }}
                    </div>
                </div>
                <div class="info-block">
                    <div class="lbl">Payment Method</div>
                    <div class="val">
                        @if ($order->payment_method === 'cod')
                            <span class="badge badge-cod">Cash on Delivery</span>
                        @elseif ($order->payment_method === 'jazzcash')
                            <span class="badge badge-jazz">JazzCash</span>
                        @else
                            <span class="badge badge-bank">Bank Transfer</span>
                        @endif
                        <br>
                        <span style="font-size:12px; color:#6b7280; text-transform:capitalize; margin-top:4px; display:block;">
                            {{ str_replace('_', ' ', $order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div style="text-align:center;">
                <a href="{{ route('order.show', $order->id) }}" class="cta-btn">View Order Details</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Bird Haven &mdash; Ethical Avian Guardianship<br>
            Questions? Email us at
            <a href="mailto:{{ \App\Models\Setting::where('key','contact_email')->value('value') ?? 'hello@birdhaven.pk' }}"
               style="color:#0c6780;">
                {{ \App\Models\Setting::where('key','contact_email')->value('value') ?? 'hello@birdhaven.pk' }}
            </a>
        </div>
    </div>
</body>
</html>

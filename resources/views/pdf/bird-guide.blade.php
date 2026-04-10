<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $bird->name }} – Care Guide | Bird Haven</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #1a1c1e;
            background: #fff;
            line-height: 1.6;
        }

        /* ── Header ───────────────────────────── */
        .header {
            background: #004f64;
            color: #fff;
            padding: 32px 40px 24px;
        }
        .header-brand {
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #7dd3ee;
            margin-bottom: 8px;
        }
        .header-title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }
        .header-subtitle {
            font-size: 13px;
            color: #b0dff0;
        }
        .header-meta {
            margin-top: 16px;
            display: flex;
            gap: 24px;
        }
        .meta-pill {
            background: rgba(255,255,255,0.12);
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 11px;
            color: #d0eef8;
        }

        /* ── Body ────────────────────────────── */
        .body {
            padding: 32px 40px;
        }

        /* ── Section ─────────────────────────── */
        .section {
            margin-bottom: 28px;
            border: 1px solid #e8edf0;
            border-radius: 10px;
            overflow: hidden;
        }
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f4f9fc;
            padding: 14px 20px;
            border-bottom: 1px solid #e8edf0;
        }
        .section-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .icon-habitat   { background: #e0f2fe; color: #0369a1; }
        .icon-nutrition { background: #fef3c7; color: #b45309; }
        .icon-social    { background: #f0fdf4; color: #166534; }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #004f64;
        }
        .section-body {
            padding: 16px 20px;
            font-size: 13px;
            color: #3d4549;
            line-height: 1.75;
        }
        .no-guide {
            color: #94a3b8;
            font-style: italic;
        }

        /* ── Specialist CTA ──────────────────── */
        .cta {
            background: #1e293b;
            color: #fff;
            border-radius: 10px;
            padding: 20px 24px;
            margin-top: 32px;
        }
        .cta-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .cta-text {
            font-size: 12px;
            color: #94a3b8;
        }

        /* ── Footer ──────────────────────────── */
        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e8edf0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-brand">Bird Haven &mdash; Care Guide</div>
        <div class="header-title">{{ $bird->name }}</div>
        @if($bird->species)
            <div class="header-subtitle">{{ $bird->species }}</div>
        @endif
        <div class="header-meta">
            @if($bird->category)
                <span class="meta-pill">{{ $bird->category->name }}</span>
            @endif
            @if($bird->age)
                <span class="meta-pill">Age: {{ $bird->age }}</span>
            @endif
            @if($bird->temperament)
                <span class="meta-pill">{{ $bird->temperament }}</span>
            @endif
        </div>
    </div>

    {{-- Body --}}
    <div class="body">

        {{-- Habitat Design --}}
        <div class="section">
            <div class="section-header">
                <div class="section-icon icon-habitat">H</div>
                <div class="section-title">Habitat Design</div>
            </div>
            <div class="section-body">
                @if($bird->habitat_guide)
                    {{ $bird->habitat_guide }}
                @else
                    <span class="no-guide">Requires a spacious aviary with structural integrity. Hardwood perches of varying diameters are essential for beak maintenance and foot health.</span>
                @endif
            </div>
        </div>

        {{-- Artisanal Nutrition --}}
        <div class="section">
            <div class="section-header">
                <div class="section-icon icon-nutrition">N</div>
                <div class="section-title">Artisanal Nutrition</div>
            </div>
            <div class="section-body">
                @if($bird->nutrition_guide)
                    {{ $bird->nutrition_guide }}
                @else
                    <span class="no-guide">A balanced diet of seeds, nuts, fresh fruits and leafy greens is essential. Avoid avocado, chocolate, caffeine, and processed foods.</span>
                @endif
            </div>
        </div>

        {{-- Social Interaction --}}
        <div class="section">
            <div class="section-header">
                <div class="section-icon icon-social">S</div>
                <div class="section-title">Social Interaction</div>
            </div>
            <div class="section-body">
                @if($bird->social_guide)
                    {{ $bird->social_guide }}
                @else
                    <span class="no-guide">Highly intelligent and emotionally sensitive. Requires a minimum of 4 hours of daily interaction to prevent psychological distress and feather destructive behaviors.</span>
                @endif
            </div>
        </div>

        {{-- Specialist CTA --}}
        <div class="cta">
            <div class="cta-title">Need Expert Advice?</div>
            <div class="cta-text">Book a virtual sanctuary walkthrough with our avian behaviorists at birdhaven.com/contact</div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <span>Bird Haven &copy; {{ date('Y') }} &mdash; {{ $bird->name }} Care Guide</span>
            <span>Generated on {{ now()->format('d M Y') }}</span>
        </div>

    </div>

</body>
</html>

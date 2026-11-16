@extends('layouts.app')

@section('title', 'Citrus Splash & Restaurant — Fresh Smoothies, Salads & Sandwiches in Abuja')

@section('content')
  {{-- ================= HERO ================= --}}
  <section class="hero">
    <div class="container hero-grid">
      <div class="hero-copy">
        <span class="eyebrow reveal"><span class="dot"></span> Fresh in Wuse, Abuja · No login needed</span>
        <h1 class="hero-title reveal d1">
          EAT CLEAN.
          <span class="pop">FRESH & DELICIOUS.</span><br />
          Fresh <span class="squiggle">juice
            <svg viewBox="0 0 200 14" preserveAspectRatio="none" aria-hidden="true"><path d="M2 10 Q 30 2 60 8 T 110 8 T 165 7 T 198 9" fill="none" stroke="#F5821F" stroke-width="6" stroke-linecap="round"/></svg>
          </span>, salads & toasty bites.
        </h1>
        <p class="lede reveal d2">Cold-pressed juices, thick smoothies, crisp salads and toasted sandwiches — made to order in Wuse, Abuja. Tap a category, add to basket, pick up or get it delivered.</p>

        {{-- category shortcuts — "From ₦…" prices come live from the database --}}
        <div class="hero-ctas reveal d3">
          <a class="cta-card juice" href="{{ route('menu') }}#smoothies">
            <span class="cta-ico">🍊</span>
            <span><strong>Smoothies</strong><small>Squeezed fresh · From ₦{{ number_format($mins['smoothies']) }}</small></span>
            <span class="arrow">→</span>
          </a>
          <a class="cta-card salad" href="{{ route('menu') }}#salads">
            <span class="cta-ico">🥗</span>
            <span><strong>Salads</strong><small>Crisp & clean · Made fresh</small></span>
            <span class="arrow">→</span>
          </a>
          <a class="cta-card sand" href="{{ route('menu') }}#sandwiches">
            <span class="cta-ico">🥪</span>
            <span><strong>Sandwiches</strong><small>Toasted · Loaded · From ₦{{ number_format($mins['sandwiches']) }}</small></span>
            <span class="arrow">→</span>
          </a>
        </div>

        <div class="hero-meta reveal d3">
          <span class="pill"><i class="g"></i> Mon–Fri 8am–7pm · Sat 10am–7pm</span>
          <span class="pill"><i class="o"></i> Pickup ~15 min</span>
          <span class="pill"><i class="y"></i> Prepaid by transfer</span>
        </div>
      </div>

      {{-- the 3D citrus still-life; data-depth powers the parallax (depth.js) --}}
      <div class="hero-art reveal d2" data-depth="5">
        <div class="orange-scene">
          <div class="orange-shadow" aria-hidden="true"></div>

          <div class="orange-main" data-depth="7">
            <img src="{{ asset('images/orange-ref.png') }}" alt="A fresh whole orange with a green leaf" />
            <span class="gloss" aria-hidden="true"></span>
          </div>

          <a class="dish-coin dc-salad" href="{{ route('menu') }}#salads" data-depth="14">
            <span class="coin"><img src="{{ asset('images/salad.jpg') }}" alt="Fresh Nigerian salad" width="104" height="104" /></span>
            <span class="coin-tag t-green">Nigerian salad</span>
          </a>
          <a class="dish-coin dc-smoothie" href="{{ route('menu') }}#smoothies" data-depth="12">
            <span class="coin"><img src="{{ asset('images/smoothie.jpg') }}" alt="Strawberry banana smoothie" width="104" height="104" /></span>
            <span class="coin-tag t-orange">Smoothies</span>
          </a>
          <a class="dish-coin dc-sandwich" href="{{ route('menu') }}#sandwiches" data-depth="16">
            <span class="coin"><img src="{{ asset('images/club-sandwich.jpg') }}" alt="Toasted club sandwich" width="104" height="104" /></span>
            <span class="coin-tag t-yellow">Club sandwich</span>
          </a>

          <div class="price-tag" data-depth="9"><span>from<br />₦{{ number_format($mins['all']) }}<small>FRESH DAILY</small></span></div>
          <span class="sticker s3" data-depth="8">no login · just order</span>
        </div>
      </div>
    </div>

    {{-- scrolling marquee strip --}}
    <div class="marquee" aria-hidden="true">
      <div class="marquee-track">
        <span>EAT CLEAN <b>🥗</b> FRESH SQUEEZED <b>🍊</b> TOASTED SANDWICHES <b>🥪</b> NO LOGIN NEEDED <b>✦</b> PICKUP OR DELIVERY <b>🛵</b>&nbsp;</span>
        <span>EAT CLEAN <b>🥗</b> FRESH SQUEEZED <b>🍊</b> TOASTED SANDWICHES <b>🥪</b> NO LOGIN NEEDED <b>✦</b> PICKUP OR DELIVERY <b>🛵</b>&nbsp;</span>
      </div>
    </div>
  </section>

  {{-- ================= CATEGORIES ================= --}}
  <section class="block">
    <div class="container">
      <div class="block-head reveal">
        <div>
          <span class="eyebrow"><span class="dot"></span> Start here</span>
          <h2 class="section-title" style="margin-top:12px">What are you craving?</h2>
          <p>Three lanes, zero scrolling through marketing. Pick one and you're in the menu.</p>
        </div>
        <a class="link-more" href="{{ route('menu') }}">Full menu →</a>
      </div>
      <div class="cat-grid">
        @foreach ($catTiles as $tile)
          <a class="cat {{ $tile['class'] }} reveal {{ $tile['delay'] }}" data-tilt href="{{ route('menu') }}#{{ $tile['slug'] }}">
            <span class="big-emoji">{{ $tile['emoji'] }}</span><span class="halftone"></span>
            <h3>{{ $tile['label'] }}</h3>
            <p>{{ $tile['desc'] }} · {{ $tile['count'] }} options</p>
            <span class="go">Browse {{ strtolower($tile['label']) }} →</span>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ================= FEATURED ================= --}}
  <section class="block" style="padding-top:0">
    <div class="container">
      <div class="block-head reveal">
        <div>
          <span class="eyebrow"><span class="dot"></span> Crowd favourites</span>
          <h2 class="section-title" style="margin-top:12px">Most-ordered this week</h2>
          <p>Tap Add — it drops straight into your basket. No page reloads, no losing your spot.</p>
        </div>
        <a class="link-more" href="{{ route('menu') }}">See all →</a>
      </div>

      {{-- the same four cards the prototype picked; names + prices live from the DB --}}
      <div class="grid-items">
        @foreach ($featured as $item)
          <article class="card reveal {{ $item['delay'] }}" data-tilt>
            <div class="card-visual {{ $item['visual'] }}"><span class="mono">{{ $item['mono'] }}</span><span class="emoji">{{ $item['emoji'] }}</span><span class="badge {{ $item['badge_class'] }}">{{ $item['badge_label'] }}</span></div>
            <div class="card-body">
              <span class="card-cat">{{ $item['cat_label'] }}</span>
              <h3 class="card-title">{{ $item['name'] }}</h3>
              <p class="card-desc">{{ $item['description'] }}</p>
              <div class="card-foot">
                <span class="price">₦{{ number_format($item['price']) }}<small>{{ $item['note'] }}</small></span>
                <button class="add-btn" data-add="{{ $item['slug'] }}">Add +</button>
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ================= HOW IT WORKS ================= --}}
  <section class="block" style="padding-top:0">
    <div class="container">
      <div class="block-head reveal">
        <div>
          <span class="eyebrow"><span class="dot"></span> Easy as 1-2-3</span>
          <h2 class="section-title" style="margin-top:12px">Order in under a minute</h2>
        </div>
      </div>
      <div class="steps">
        <div class="step reveal"><div class="step-ico">🧺</div><h3>1. Pick your cravings</h3><p>Browse the menu, tap Add. Your basket follows you — no account needed.</p></div>
        <div class="step reveal d1"><div class="step-ico">📝</div><h3>2. Checkout as guest</h3><p>Name + phone, pickup or delivery. Everything is prepaid online — pay by transfer, then we prepare your order.</p></div>
        <div class="step reveal d2"><div class="step-ico">🔔</div><h3>3. Track & collect</h3><p>Get your order number instantly. Track it with your phone number. We'll have it hot & fresh.</p></div>
      </div>
      <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:20px" class="reveal">
        <a class="btn btn-primary btn-lg" href="{{ route('menu') }}">Start an order 🍊</a>
        <a class="btn btn-secondary btn-lg" href="{{ route('status') }}">Track my order</a>
      </div>
    </div>
  </section>

  {{-- ================= ABOUT / VISIT ================= --}}
  <section class="block" style="padding-top:0">
    <div class="container split">
      <div class="about-card reveal">
        <span class="eyebrow" style="background:var(--yellow); color:var(--ink); border:2px solid #fff">Our story 🍋</span>
        <h2 style="margin-top:14px">Abuja's healthy food & beverage spot.</h2>
        <p>Eat clean, fresh & delicious — that's the whole idea. Smoothies squeezed when you order, salads tossed crisp, sandwiches toasted golden. Nothing sitting around. Nothing boring.</p>
        <p style="margin-top:10px">Find us at Stick and Stone Plaza in Wuse — come say hi, grab a cup, watch us press it fresh.</p>
        <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:18px">
          <a class="btn btn-secondary" href="{{ route('about') }}">About & contact →</a>
          <a class="btn btn-ghost" style="color:#fff; border-color:rgba(255,255,255,.3)" href="https://www.instagram.com/citrussplash_res/" target="_blank" rel="noopener" aria-label="Instagram">📸 @citrussplash_res</a>
        </div>
      </div>
      <div style="display:grid; gap:12px">
        <div class="about-visual reveal d1"><span class="big">🍹</span><span class="sticker s1" style="top:14px; left:14px">fresh daily</span><span class="sticker s2" style="bottom:14px; right:14px">Wuse, Abuja 🌞</span></div>
        <div class="visit-grid">
          <div class="visit reveal"><h3>📍 Find us</h3><p>Suite A105, First Floor,<br />Stick and Stone Plaza<br />(same building as GTBank),<br />Ademola Adetokunbo, Wuse, Abuja</p></div>
          <div class="visit reveal d1"><h3>⏰ Hours</h3><p>Mon–Fri · 8am – 7pm<br />Sat · 10am – 7pm<br />Sun · Closed</p></div>
          <div class="visit reveal d2"><h3>📞 Call us</h3><p><a href="tel:+2348171539888">0817 153 9888</a><br /><a href="tel:+2349079821327">0907 982 1327</a><br /><a href="tel:+2347044391134">0704 439 1134</a></p></div>
        </div>
      </div>
    </div>
  </section>
@endsection

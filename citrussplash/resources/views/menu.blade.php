@extends('layouts.app')

@section('title', 'Menu — Citrus Splash & Restaurant, Abuja')
@section('meta-description', 'Full menu: 49 smoothies, 11 sandwiches, salads and more from Citrus Splash, Wuse Abuja. Add to basket and checkout as guest.')
@section('footer-note', 'Suite A105, Stick and Stone Plaza, Wuse, Abuja · Mon–Fri 8–7, Sat 10–7.')

@section('content')
  {{-- ================= PAGE HERO + SEARCH ================= --}}
  <section class="page-hero">
    <div class="container">
      <span class="eyebrow"><span class="dot"></span> Our menu · Wuse, Abuja</span>
      <h1 class="page-title">Pick your craving. <span class="pop">We press it fresh.</span></h1>
      <p class="lede">Tap Add and it drops into your basket — no account, no reloads. Prices as in store, subject to change.</p>
      <div class="search-wrap">
        <span class="search-ico" aria-hidden="true">🔍</span>
        <input id="menuSearch" type="search" placeholder="Search smoothies, sandwiches, salads…" aria-label="Search menu" autocomplete="off" />
        <button id="searchClear" class="search-clear" style="display:none" aria-label="Clear search">✕</button>
      </div>
    </div>
  </section>

  {{-- ================= STICKY TABS ================= --}}
  <div class="tab-bar" id="tabBar">
    <div class="container tab-scroll">
      <div class="tabs" id="menuTabs" role="tablist" aria-label="Menu categories"></div>
    </div>
  </div>

  {{-- ================= MENU SECTIONS ================= --}}
  {{-- Cards are rendered client-side by menu.js from the live /js/menu-data.js --}}
  <section class="block menu-block">
    <div class="container">
      <p class="count-line" id="resultCount" role="status"></p>
      <div id="menuSections"></div>
      <div class="empty" id="menuEmpty" style="display:none">
        <div class="big">🔍</div>
        <p><strong>Nothing matches that craving.</strong><br />Try "mango", "chicken" or "avocado".</p>
        <p><button class="btn btn-secondary" data-reset>Show full menu</button></p>
      </div>

      <div class="price-note">
        <strong>📌 Prices are subject to change</strong> — same as our in-store menu at Stick and Stone Plaza, Wuse.
        Something look off? Call us: <a href="tel:+2348171539888">0817 153 9888</a>
      </div>
    </div>
  </section>
@endsection

{{-- Floating basket bar — menu page only (main.js fills and shows it) --}}
@section('floating-cart')
<button class="cart-bar" id="cartBar" data-open-cart aria-label="View basket">
  <span>🧺 <b id="cartBarText">View basket</b></span>
  <span class="cart-bar-go">→</span>
</button>
@endsection

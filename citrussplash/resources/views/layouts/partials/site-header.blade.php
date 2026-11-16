{{--
  Site header: sticky bar with the hand-drawn orange logo, desktop nav,
  basket button and the mobile hamburger. Below it, the collapsible
  mobile nav. Written once here — every page gets it from the layout.
  The "active" underline follows the current route automatically.
--}}
<header class="site-head">
  <div class="container head-inner">
    <a class="brand" href="{{ route('home') }}" aria-label="Citrus Splash home">
      <svg class="brand-mark" viewBox="0 0 100 100" aria-hidden="true">
        <circle cx="50" cy="56" r="36" fill="#F5821F" stroke="#2B2B2B" stroke-width="6"/>
        <circle cx="50" cy="56" r="24" fill="#FFE3B8"/>
        <g stroke="#F5821F" stroke-width="6" stroke-linecap="round">
          <path d="M50 56V36"/><path d="M50 56l17 12"/><path d="M50 56l-17 12"/>
          <path d="M50 56l17-6"/><path d="M50 56l-17-6"/>
        </g>
        <circle cx="50" cy="56" r="5" fill="#F5821F" stroke="#2B2B2B" stroke-width="3"/>
        <path d="M68 18C60 10 46 10 40 18c8 8 20 8 28 0z" fill="#4C9A2A" stroke="#2B2B2B" stroke-width="4"/>
      </svg>
      <span class="brand-name">Citrus Splash<span>& RESTAURANT</span></span>
    </a>

    <nav class="nav-desk" aria-label="Primary">
      <a href="{{ route('home') }}" @if(request()->routeIs('home')) class="active" @endif>Home</a>
      <a href="{{ route('menu') }}" @if(request()->routeIs('menu')) class="active" @endif>Menu</a>
      <a href="{{ route('status') }}" @if(request()->routeIs('status')) class="active" @endif>Track Order</a>
      <a href="{{ route('about') }}" @if(request()->routeIs('about')) class="active" @endif>Contact</a>
    </nav>

    <div class="head-actions">
      <a class="track-link" href="{{ route('status') }}">📦 Track order</a>
      <button class="cart-btn" id="cartBtn" data-open-cart aria-label="Open basket">
        🧺 <span id="cartCountHead" class="cart-count">0</span>
      </button>
      <button class="menu-toggle" id="menuToggle" aria-expanded="false" aria-label="Open menu">☰</button>
    </div>
  </div>
</header>

<nav class="mobile-nav" id="mobileNav" aria-label="Mobile">
  {{-- the current page gets the bold treatment, like the prototype --}}
  <a href="{{ route('home') }}">@if(request()->routeIs('home'))<strong>🏠 Home</strong>@else 🏠 Home @endif</a>
  <a href="{{ route('menu') }}">@if(request()->routeIs('menu'))<strong>🍽️ Menu</strong>@else 🍽️ Menu @endif</a>
  <a href="{{ route('status') }}">📦 Track Order</a>
  <a href="{{ route('about') }}">📍 Contact & Hours</a>
</nav>

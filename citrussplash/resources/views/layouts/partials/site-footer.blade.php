{{--
  Site footer: the wave divider into the dark strip, brand block,
  link grid and the base line. The intro sentence can be overridden
  per page via the 'footer-note' section (the prototype varies it).
--}}
<footer>
  <div class="foot-wave"></div>
  <div class="container foot-inner">
    <div>
      <div class="foot-brand">
        <svg class="brand-mark" viewBox="0 0 100 100" aria-hidden="true" style="filter:none">
          <circle cx="50" cy="56" r="36" fill="#F5821F" stroke="#fff" stroke-width="6"/>
          <circle cx="50" cy="56" r="24" fill="#FFE3B8"/>
          <g stroke="#F5821F" stroke-width="6" stroke-linecap="round"><path d="M50 56V36"/><path d="M50 56l17 12"/><path d="M50 56l-17 12"/></g>
          <path d="M68 18C60 10 46 10 40 18c8 8 20 8 28 0z" fill="#4C9A2A" stroke="#fff" stroke-width="4"/>
        </svg>
        <span class="brand-name">Citrus Splash<span>& RESTAURANT</span></span>
      </div>
      <p class="small" style="color:#CBBFAE; max-width:38ch; margin:12px 0 0">@yield('footer-note', 'Fresh smoothies, salads & toasted sandwiches in Wuse, Abuja. Guest checkout, no login — eat clean, fresh & delicious.')</p>
    </div>
    <div>
      <div class="foot-links">
        <a href="{{ route('home') }}">Home</a><a href="{{ route('menu') }}">Menu</a><a href="{{ route('status') }}">Track Order</a><a href="{{ route('about') }}">Contact</a><a href="https://www.instagram.com/citrussplash_res/" target="_blank" rel="noopener">Instagram 📸</a>
      </div>
      <p class="small" style="color:#CBBFAE; margin:12px 0 0">*All orders are prepaid by bank transfer — no cash, no pay-on-arrival.</p>
    </div>
  </div>
  <div class="container foot-base">
    <span>© <span id="year">2026</span> Citrus Splash & Restaurant, Abuja. Made fresh daily 🍊</span>
    <span>Pickup ~15 min · Delivery 30–45 min</span>
  </div>
</footer>

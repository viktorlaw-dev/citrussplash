{{--
  Page <head>: charset, viewport, SEO title + description (overridable per page),
  Google Fonts (Baloo 2 + Inter — the only external asset, per DBD §3),
  the compiled SASS/JS bundle (@vite) and the hand-drawn orange favicon.
--}}
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Citrus Splash & Restaurant — Fresh Smoothies, Salads & Sandwiches in Abuja')</title>
  <meta name="description" content="@yield('meta-description', 'Order fresh smoothies, salads and toasted sandwiches from Citrus Splash in Wuse, Abuja. Pickup or delivery, no login needed.')" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  @vite(['resources/sass/style.scss', 'resources/js/app.js'])
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='54' r='38' fill='%23F5821F' stroke='%232B2B2B' stroke-width='6'/><circle cx='50' cy='54' r='26' fill='%23FFD9A8'/><path d='M50 54L50 28M50 54L72 68M50 54L28 68' stroke='%23F5821F' stroke-width='6' stroke-linecap='round'/><path d='M68 18C60 10 46 10 40 18c8 8 20 8 28 0z' fill='%234C9A2A' stroke='%232B2B2B' stroke-width='4'/></svg>" />
</head>

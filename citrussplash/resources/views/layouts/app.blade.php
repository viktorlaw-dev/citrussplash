<!doctype html>
<html lang="en">
@include('layouts.partials.head')
<body data-page="{{ $page ?? '' }}">

@include('layouts.partials.site-header')

<main>
  @yield('content')
</main>

@include('layouts.partials.site-footer')
@include('layouts.partials.overlays')
@yield('floating-cart')

{{--
  The menu as JavaScript, served live from the database (same JSON shape
  the prototype's static file had). A classic script tag at the end of the
  body runs BEFORE our bundled module, so window.CS_MENU is always ready
  when the cart and menu scripts wake up.
--}}
<script src="/js/menu-data.js"></script>
</body>
</html>

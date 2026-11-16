{{-- About & contact — placeholder while Phase 4 ports the remaining pages.
    The real page (story, hours table, FAQ, Instagram card, map) replaces
    this file later in Phase 4. --}}
@extends('layouts.app')

@section('title', 'Contact & Hours — Citrus Splash & Restaurant, Abuja')

@section('content')
  <section class="page-hero">
    <div class="container">
      <span class="eyebrow"><span class="dot"></span> Contact & hours</span>
      <h1 class="page-title">The about page is being rebuilt <span class="pop">right now.</span></h1>
      <p class="lede">Meanwhile: Suite A105, Stick and Stone Plaza, Wuse, Abuja · Mon–Fri 8am–7pm · Sat 10am–7pm · 0817 153 9888.</p>
      <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:20px">
        <a class="btn btn-secondary" href="{{ route('menu') }}">← Back to the menu</a>
      </div>
    </div>
  </section>
@endsection

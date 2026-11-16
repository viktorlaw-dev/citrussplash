{{-- Order status / tracker — placeholder while Phase 4 ports the remaining pages.
    The real tracker (stage hero + vertical timeline + countdown) replaces
    this file later in Phase 4. --}}
@extends('layouts.app')

@section('title', 'Track Order — Citrus Splash & Restaurant, Abuja')

@section('content')
  <section class="page-hero">
    <div class="container">
      <span class="eyebrow"><span class="dot"></span> Track order</span>
      <h1 class="page-title">Order tracking is being rebuilt <span class="pop">right now.</span></h1>
      <p class="lede">This page arrives in Phase 4's second batch — check back tomorrow and track a real order by phone number.</p>
      <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:20px">
        <a class="btn btn-secondary" href="{{ route('menu') }}">← Back to the menu</a>
      </div>
    </div>
  </section>
@endsection

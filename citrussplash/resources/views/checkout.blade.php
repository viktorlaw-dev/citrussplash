{{-- Checkout — placeholder while Phase 4 ports the remaining pages.
    The real form (guest details, pickup/delivery, bank-transfer flow)
    replaces this file later in Phase 4. --}}
@extends('layouts.app')

@section('title', 'Checkout — Citrus Splash & Restaurant, Abuja')

@section('content')
  <section class="page-hero">
    <div class="container">
      <span class="eyebrow"><span class="dot"></span> Almost there</span>
      <h1 class="page-title">Checkout is being rebuilt <span class="pop">right now.</span></h1>
      <p class="lede">This page arrives in Phase 4's second batch — the design, drawer and basket you just used are already live everywhere else.</p>
      <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:20px">
        <a class="btn btn-secondary" href="{{ route('menu') }}">← Back to the menu</a>
      </div>
    </div>
  </section>
@endsection

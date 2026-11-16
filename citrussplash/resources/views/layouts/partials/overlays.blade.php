{{--
  Overlays: the basket drawer (slides from the side / bottom), the dim
  scrim behind it, and the confirmation toast. Shared by every page —
  main.js opens and closes them. The basket lives in localStorage;
  the database becomes the truth at checkout (rule F3).
--}}
<div class="scrim" id="scrim"></div>

<aside class="drawer" id="cartDrawer" aria-hidden="true" aria-label="Shopping basket">
  <div class="drawer-head">
    <div><h2>Your basket 🧺</h2><small><span id="cartCount">0</span> items · pickup or delivery at checkout</small></div>
    <button class="close-x" data-close-cart aria-label="Close basket">✕</button>
  </div>
  <div class="drawer-items" id="cartItems"></div>
  <div class="drawer-foot" id="cartFoot">
    <div class="tot-row"><span>Subtotal</span><span id="subTotal">₦0</span></div>
    <div class="tot-row"><span>Delivery fee</span><span>Chosen at checkout</span></div>
    <div class="fee-note">🏦 Prepaid by transfer — we start preparing once payment lands.</div>
    <div class="tot-row grand"><span>Total</span><span id="grandTotal">₦0</span></div>
    <a class="btn btn-primary btn-block btn-lg" style="margin-top:12px" href="{{ route('checkout') }}">Checkout →</a>
    <button class="btn btn-ghost btn-block" data-close-cart style="margin-top:8px">Keep browsing</button>
  </div>
</aside>

<div class="toast" id="toast" role="status"><span class="tick">✓</span><span id="toastText">Added to basket</span></div>

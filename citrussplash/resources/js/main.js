// Citrus Splash — Shared cart + interactions (home, menu, checkout…)
// Cart drawer + badge bump + toast + reveal + mobile nav + floating basket bar.
// Ported 1:1 from the prototype; the only change is the shared esc() import (rule F6).
import { esc } from "./esc.js";

(function () {
  const N = (n) => "₦" + Number(n).toLocaleString("en-NG");

  // Catalog: full menu when menu-data.js is loaded, else real featured fallback
  const MENU = {};
  function kindFor(cat) {
    if (cat === "sandwiches") return "sand";
    if (cat === "salads") return "salad";
    return "juice";
  }
  (function buildCatalog() {
    const data = (window.CS_MENU && window.CS_MENU.items) || [];
    if (data.length) {
      data.forEach((it) => { MENU[it.id] = { id: it.id, name: it.name, price: it.price, kind: kindFor(it.cat), emoji: it.emoji || "🍊" }; });
      return;
    }
    [
      ["club", "Club Sandwich", 5200, "sand", "🥪"],
      ["abuja-tonic", "Abuja Tonic", 4550, "juice", "🍉"],
      ["sp-special", "SP Special", 5200, "sand", "🥪"],
      ["orange-juice", "Orange Juice", 3450, "juice", "🍊"]
    ].forEach((a) => { MENU[a[0]] = { id: a[0], name: a[1], price: a[2], kind: a[3], emoji: a[4] }; });
  })();

  let cart = {};
  try { cart = JSON.parse(localStorage.getItem("cs_cart") || "{}"); } catch (e) { cart = {}; }

  const $ = (s, r) => (r || document).querySelector(s);
  const $$ = (s, r) => Array.from((r || document).querySelectorAll(s));

  const drawer = $("#cartDrawer");
  const scrim = $("#scrim");
  const itemsEl = $("#cartItems");
  const countEls = [$("#cartCount"), $("#cartCountHead")].filter(Boolean);
  const subEl = $("#subTotal");
  const grandEl = $("#grandTotal");
  const footEl = $("#cartFoot");
  const toast = $("#toast");
  const toastText = $("#toastText");
  let toastT;

  function save() { try { localStorage.setItem("cs_cart", JSON.stringify(cart)); } catch (e) {} }
  function count() { return Object.values(cart).reduce((a, b) => a + b, 0); }
  function subtotal() { return Object.entries(cart).reduce((a, [id, q]) => a + (MENU[id] ? MENU[id].price * q : 0), 0); }

  // Floating "View basket" bar (menu page only — element exists there)
  function updateBar() {
    const bar = document.getElementById("cartBar");
    if (!bar) return;
    const c = count();
    bar.classList.toggle("show", c > 0);
    const label = document.getElementById("cartBarText");
    if (label) label.textContent = "View basket · " + c + (c === 1 ? " item" : " items") + " · " + N(subtotal());
    // Notify page scripts (e.g. checkout summary) that the cart changed
    try { document.dispatchEvent(new CustomEvent("cs:cart")); } catch (e) {}
  }

  function bump() {
    const btn = $("#cartBtn");
    if (!btn) return;
    btn.classList.remove("bump");
    void btn.offsetWidth;
    btn.classList.add("bump");
  }

  function showToast(msg) {
    if (!toast) return;
    toastText.textContent = msg;
    toast.classList.add("show");
    clearTimeout(toastT);
    toastT = setTimeout(() => toast.classList.remove("show"), 2200);
  }

  function render() {
    const ids = Object.keys(cart).filter((id) => cart[id] > 0 && MENU[id]);
    const c = count();
    countEls.forEach((el) => { el.textContent = c; });
    if (!itemsEl) return;

    if (!ids.length) {
      itemsEl.innerHTML =
        '<div class="empty"><div class="big">🧺</div>' +
        "<p><strong>Your basket is empty.</strong><br>Add something juicy to get started.</p></div>";
      if (footEl) footEl.style.display = "none";
      updateBar();
      return;
    }
    if (footEl) footEl.style.display = "";
    itemsEl.innerHTML = ids.map((id) => {
      const m = MENU[id], q = cart[id];
      return (
        '<div class="line-item">' +
          '<div class="line-emoji ' + m.kind + '">' + m.emoji + "</div>" +
          '<div class="line-info"><strong>' + esc(m.name) + "</strong><span>" + N(m.price) + " each</span></div>" +
          '<div class="stepper" data-id="' + id + '">' +
            '<button data-act="dec" aria-label="Reduce quantity">−</button><b>' + q + "</b>" +
            '<button data-act="inc" aria-label="Increase quantity">+</button>' +
          "</div>" +
        "</div>"
      );
    }).join("");

    const sub = subtotal();
    if (subEl) subEl.textContent = N(sub);
    if (grandEl) grandEl.textContent = N(sub);
    save();
    updateBar();
  }

  // Arc an emoji from the tapped button into the basket (balanced motion)
  function fly(fromEl, emoji) {
    const target = document.getElementById("cartBtn");
    if (!fromEl || !target) return;
    if (window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    const a = fromEl.getBoundingClientRect();
    const b = target.getBoundingClientRect();
    const dot = document.createElement("div");
    dot.className = "fly-dot";
    dot.textContent = emoji || "🍊";
    dot.setAttribute("aria-hidden", "true");
    dot.style.left = (a.left + a.width / 2 - 17) + "px";
    dot.style.top = (a.top + a.height / 2 - 17) + "px";
    document.body.appendChild(dot);
    const dx = (b.left + b.width / 2) - (a.left + a.width / 2);
    const dy = (b.top + b.height / 2) - (a.top + a.height / 2);
    const lift = Math.min(130, Math.abs(dy) / 2 + 50);
    try {
      const anim = dot.animate([
        { transform: "translate(0,0) scale(1)", opacity: 1 },
        { transform: "translate(" + (dx * 0.5) + "px," + ((dy * 0.5) - lift) + "px) scale(.85)", opacity: 1, offset: 0.55 },
        { transform: "translate(" + dx + "px," + dy + "px) scale(.35)", opacity: 0.9 }
      ], { duration: 600, easing: "cubic-bezier(.32,.72,.28,1)" });
      anim.onfinish = function () { dot.remove(); };
    } catch (e) { dot.remove(); }
  }

  function add(id) {
    if (!MENU[id]) return;
    const btn = document.querySelector('[data-add="' + id + '"]');
    fly(btn, MENU[id].emoji);
    cart[id] = (cart[id] || 0) + 1;
    render(); bump();
    showToast(MENU[id].name + " added to basket");
    if (btn) {
      btn.classList.add("added");
      const old = btn.innerHTML;
      btn.innerHTML = "✓ Added";
      setTimeout(() => { btn.classList.remove("added"); btn.innerHTML = old; }, 1100);
    }
  }

  function openCart() {
    if (!drawer) return;
    drawer.classList.add("open");
    drawer.setAttribute("aria-hidden", "false");
    if (scrim) scrim.classList.add("show");
    document.body.style.overflow = "hidden";
  }
  function closeCart() {
    if (!drawer) return;
    drawer.classList.remove("open");
    drawer.setAttribute("aria-hidden", "true");
    if (scrim) scrim.classList.remove("show");
    document.body.style.overflow = "";
  }

  document.addEventListener("click", (e) => {
    const addBtn = e.target.closest("[data-add]");
    if (addBtn) { add(addBtn.getAttribute("data-add")); return; }

    const st = e.target.closest(".stepper button");
    if (st) {
      const wrap = st.closest(".stepper");
      const id = wrap.getAttribute("data-id");
      const act = st.getAttribute("data-act");
      cart[id] = (cart[id] || 0) + (act === "inc" ? 1 : -1);
      if (cart[id] <= 0) delete cart[id];
      render();
      return;
    }

    if (e.target.closest("[data-open-cart]")) { openCart(); return; }
    if (e.target.closest("[data-close-cart]")) { closeCart(); return; }

    const tg = e.target.closest("#menuToggle");
    if (tg) {
      const nav = $("#mobileNav");
      const open = nav.classList.toggle("open");
      tg.setAttribute("aria-expanded", open ? "true" : "false");
    }
  });

  document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeCart(); });
  // Let checkout clear the cart after a successful order
  document.addEventListener("cs:cart:clear", () => { cart = {}; save(); render(); });
  if (scrim) scrim.addEventListener("click", closeCart);

  // scroll reveal
  const io = new IntersectionObserver((entries) => {
    entries.forEach((en) => { if (en.isIntersecting) { en.target.classList.add("in"); io.unobserve(en.target); } });
  }, { threshold: 0.12 });
  $$(".reveal").forEach((el) => io.observe(el));

  const y = $("#year");
  if (y) y.textContent = new Date().getFullYear();

  render();
})();

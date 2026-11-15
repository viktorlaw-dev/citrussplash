# CitrusSplash — Application Flows
How the app actually behaves, end to end. `PRD.md` says *what*; this says *how it flows*.

---

## 1. Customer happy path (pickup)

```
 Home ──▶ Menu ──▶ Add items (cart drawer · localStorage)
   │                          │
   │                          ▼
   │                    Checkout page (guest: name + NG phone)
   │                          │  pickup ✓ · transfer ✓
   │                          ▼
   │              POST /api/orders  (server re-prices, validates, Turnstile)
   │                          │
   │                          ▼
   │        Success screen: CS-xxxx · total · bank details
   │        "Send the transfer with CS-xxxx as the narration"
   │                          │
   │                          ▼
   │        Customer transfers (bank app) · narration = CS-xxxx
   │                          │
   │                          ▼
   │        Staff sees order (chime) ──▶ matches narration ──▶
   │        "Payment received" ──▶ status Confirmed ──▶ Preparing
   │                          │
   │                          ▼
   └──▶ Tracker page (order no + phone · polls every 30 s)
              Ready for pickup ──▶ customer collects ──▶ Completed
   (optional) Staff clicks "Notify on WhatsApp" → prefilled message → tap send
```

## 2. Delivery path
Identical until payment confirmed, then:
`Preparing → Out for delivery → Completed`. Address is required at checkout;
delivery fee (Settings) is added to the total.

## 3. Order status state machine

```
                 ┌────────────── payment confirmed? ──────────────┐
                 │ no                    │ yes                    │
                 ▼                       ▼                        │
 received ─────────────▶ confirmed ──▶ preparing ──┬─▶ ready ────────▶ completed   (pickup)
     │                                            └─▶ out_for_delivery ─▶ completed (delivery)
     │
     ├─▶ cancelled (staff, reason: manual)          ← final
     └─▶ cancelled (auto, reason: payment_timeout)  ← restorable by staff
```

**Rules**
- Kitchen work starts only after payment is confirmed (Architecture-essentials D7)
- The customer tracker maps 5 visible stages (unchanged from the prototype):
  `Received → Confirmed → Preparing → Ready | Out for Delivery → Completed`
- A cancelled order shows a kind, clear notice in the tracker (with restore hint if payment-timeout)

## 4. Unpaid-order timeout (FR-7)

1. Order sits in `received` with `payment_status = pending`
2. Tracker shows a live countdown: *"⏳ Payment not received — order auto-cancels in 42 minutes"*
3. Scheduler (every 5 min) cancels unpaid orders older than `settings.auto_cancel_minutes` (default 60)
4. Timed-out order → `cancelled` + `payment_timeout` → audit-logged → **staff can restore**
   (e.g., customer shows a late transfer receipt)

## 5. Admin flows

### Login (FR-8)
```
/admin ──▶ middleware: no session? ──▶ /admin/login
            1. password + TOTP code
            2. 5 fails / 15 min → lockout (account + IP)
            3. success → session (60-min idle timeout) → audit: login.success
```

### Daily loop
```
New order chimes ──▶ verify transfer narration in bank app ──▶
"Payment received" ──▶ status dropdown (Preparing → Ready/Out for delivery → Completed) ──▶
"Notify on WhatsApp" button when ready ──▶ next order
```

### Management
Menu CRUD (name, price, description, emoji, tag, availability, ordering) ·
Settings (bank details, delivery fee, ETAs, WhatsApp templates, auto-cancel minutes) ·
Audit log (owner).

## 6. Edge cases & their answers

| Case | Behavior |
|---|---|
| Empty basket at checkout | Show the existing empty state; no API call |
| Invalid phone / short name / missing delivery address | Inline field errors (frontend) **and** server validation (API 422 with messages) |
| Tampered prices | Server re-prices from DB; client prices ignored; totals are DB truth |
| Double-submit / spam bots | Turnstile + rate limit (5/min/IP) + submit button disables on click |
| Tracking lookup with wrong phone | Generic 404 message: "Can't find that order — check the number and phone, or call us" |
| Order number typed slightly wrong | Same generic message (no information leak) |
| Payment arrives after auto-cancel | Staff restores the order; customer re-checks tracker |
| Status changes while customer's tracker is open | Next 30-s poll picks it up (existing auto-refresh logic, now on a real endpoint) |
| Store closed (Sun) | v1: orders still accepted (v2 banner deferred) — noted in PRD out-of-scope |
| WhatsApp notify tapped | `wa.me/<customer phone>?text=<template from Settings with order details>` — staff taps send |
| Customer orders by phone instead of the website | Staff enters it in `/admin/orders/create` (`source: phone`) — same pricing/validation; customer gets the CS number + bank details and transfers as usual |

## 7. What the customer sees at each status (copy from the prototype)

| Stage | Emoji | Title | Note |
|---|---|---|---|
| 0 | 🧾 | Order received | "We're confirming it now" |
| 1 | ✅ | Order confirmed | "We'll start preparing shortly" |
| 2 | 🧃 | Being prepared | "Juicers whirring, toasters toasting" |
| 3a | 🛍️ | Ready for pickup | "Come grab it at Stick & Stone Plaza" |
| 3b | 🛵 | Out for delivery | "Your rider is on the way" |
| 4 | 🎉 | Completed | "Enjoy! Come back hungry" |

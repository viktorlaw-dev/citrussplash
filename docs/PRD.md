# CitrusSplash & Restaurant — Product Requirements Document (PRD)

| | |
|---|---|
| Project | CitrusSplash ordering web application |
| Owner | Victor Lawrence (beginner developer, learning full-stack) |
| Status | Approved plan — governs all build work |
| Companion docs | Architecture.md · Architecture-essentials.md · APP flow.md · DBD.md · BSD.md · Agent.md · openCode.md |

---

## 1. What this is

CitrusSplash & Restaurant is a **real food business**: Suite A105, First Floor, Stick and Stone Plaza
(same building as GTBank), Ademola Adetokunbo, Wuse, Abuja. It sells freshly made smoothies,
salads and toasted sandwiches.

An existing **static frontend prototype** (HTML + CSS + vanilla JS, no backend) already exists and its
design is approved. This project converts it into a **working ordering web application**:

- Customers order **as guests** (no accounts, no login) — name + phone only
- Pickup or delivery, **prepaid by bank transfer only** (v1)
- Customers **track their order live** by order number + phone
- Staff run the day from a **secure admin panel** (login + 2FA): confirm payments, advance status,
  edit the menu, change settings
- Orders, menu and settings live in a **real database** via **Laravel (PHP)**

This is simultaneously a **guided learning project**: the owner must understand every line of code
that ships. The learning contract lives in `Agent.md` and is binding.

## 2. Goals

| # | Goal | Success measure |
|---|---|---|
| G1 | Real orders reach the store | An order placed on the site appears in the admin panel within seconds |
| G2 | Staff can run daily operations with zero code edits | Prices, availability, statuses, settings all editable in admin |
| G3 | Admin is difficult to access | `/admin` requires password + authenticator code; lockouts and audit log active |
| G4 | Keep the approved design | Visual result identical to the current prototype (verified side-by-side) |
| G5 | Owner learns frontend + backend fundamentals | Owner can explain every file's purpose and pass mini-task checks (Agent.md) |

## 3. Users

| User | Description | Auth |
|---|---|---|
| **Customer** | Anyone in Abuja ordering food; mobile-first, cheap Android on 3G/4G. Never creates an account | None (guest) |
| **Staff** | 1–5 kitchen/counter staff. Confirms payments, advances order status, uses WhatsApp send buttons | Password + TOTP 2FA |
| **Owner** | Victor — the developer-learner. Creates staff accounts, edits settings, reads the audit log | Same as staff + owner role |

## 4. Functional requirements (v1 scope)

| ID | Requirement | Phase |
|---|---|---|
| FR-1 | Menu browsing: 3 categories (49 smoothies, 11 sandwiches, 4 salads), search, sticky category tabs, sold-out awareness | 4–5 |
| FR-2 | Cart: basket drawer, quantities, live totals. **Display pricing only — server re-prices on order** | 4–6 |
| FR-3 | Guest checkout: name + Nigerian phone, pickup/delivery choice, address (delivery), transfer-only payment | 4–6 |
| FR-4 | Order creation API: server-side re-pricing from DB, sequential server-issued `CS-xxxx` numbers, full validation, Cloudflare Turnstile, rate limiting | 5–8 |
| FR-5 | Payment: bank transfer only. Account details served from Settings. Narration = order number. Staff verifies by narration matching and presses "Payment received" | 5–7 |
| FR-6 | Order tracking: lookup by order no + phone, 5-stage timeline, 30-second auto-refresh | 5–6 |
| FR-7 | Unpaid-order auto-cancel after **configurable minutes (default 60)**; customer sees a live countdown; staff can restore a timed-out order | 7 |
| FR-8 | Admin panel: login (password → TOTP code), 5-tries/15-min lockout, live orders table with status filters + phone/name search, order detail, status changes, payment confirmation | 7 |
| FR-9 | Notifications: kitchen = chime + auto-refresh on the orders page; customer = per-order **WhatsApp tap-to-send** button (message template editable in Settings) | 7 |
| FR-10 | Menu management: CRUD categories and items, availability toggle, ordering, tags (pop/new/fresh) | 7 |
| FR-11 | Settings: bank details, delivery fee, ETAs, WhatsApp templates, auto-cancel minutes, store phones | 7 |
| FR-12 | Audit log: logins (success/fail), every order status change, payment confirmations, menu/settings edits | 7 |
| FR-13 | Legal pages: simple Privacy Policy + Terms (NDPR-aware, plain language) | 4 |
| FR-14 | Menu served dynamically at the same URL/shape as the old `js/menu-data.js` so page scripts keep working unchanged | 5 |
| FR-15 | Staff can enter phone/walk-in orders manually (marked `source: phone`) using the same server-side pricing/validation; the customer then transfers with the CS number as narration | 7 |

## 5. Explicitly OUT of scope (v2 shelf — do not build in v1)

- Card/online payments (Paystack/Flutterwave)
- Automated WhatsApp (Cloud API: Meta verification, templates, per-message fees) or SMS (Termii)
- Customer accounts, order history, loyalty
- Item photography beyond the existing 3 photos (emoji tiles are the v1 visual)
- SEO extras (Open Graph, structured data), analytics
- Closed-Sunday ordering banner, delivery zones/variable fees, rider assignment
- WebP image pipeline, full accessibility audit

## 6. Business facts — single source of truth

- **Address:** Suite A105, First Floor, Stick and Stone Plaza (same building as GTBank), Ademola Adetokunbo, Wuse, Abuja
- **Hours:** Mon–Fri 8:00–19:00 · Sat 10:00–19:00 · **Sun closed**
- **Phones:** 0817 153 9888 · 0907 982 1327 · 0704 439 1134
- **Instagram:** @citrussplash_res
- **ETAs (current copy):** pickup ~15–20 min · delivery ~30–45 min
- **Delivery fee:** ₦1,500 is a **placeholder — store must confirm**
- **Payment:** bank transfer, prepaid; no cash, no pay-on-arrival

### Open items owed by the store (blockers for LAUNCH, not for building)
1. Real bank account details (name, number, bank) → entered in Settings
2. Confirm delivery fee
3. Two sandwich prices were transcribed blind (glare on menu photo) — items entered at ₦5,600 must be verified
4. Verify whether "Coconut Island" smoothie really contains **white rum** (affects who can order it)
5. Owner creates a free Cloudflare account to obtain Turnstile site/secret keys

## 7. Non-functional requirements

| Area | Requirement |
|---|---|
| Learnability | Beginner-readable code: small files, generous early comments, every concept in `LEARNING.md` glossary (repo root) |
| Performance | Mobile-first; fast on cheap Android + 3G; no heavy frameworks; images optimized |
| Security | Full baseline in `Architecture-essentials.md` (server re-pricing, escaping, secrets in `.env`, 2FA, rate limits, Turnstile, audit) |
| Privacy | Collect only name/phone/address/order contents (NDPR-aware); no marketing reuse without consent |
| Accessibility | Preserve existing aria labels/focus patterns; respect `prefers-reduced-motion` everywhere (including depth/tilt effects — current gap to fix) |
| Timezone | All order times & business logic run on **Africa/Lagos** |
| Data integrity | Money as integers (naira); orders snapshot item names/prices at creation; history never rewritten |

## 8. Milestones

Build proceeds in 8 phases with strict Definition-of-Done checklists — see `Agent.md` §Phases.
Phase 1 (git + GitHub) must happen before any other code work.

## 9. Timeline — LOCKED: 10-day build (owner decision, Nov 2026)

**Mode:** agent-heavy build · owner commits **~1 hr/day** (run commands, test in browser, decide).
Explanations live in `LEARNING.md` + inline notes (deep teach-back scheduled after launch).

| Day | Work | Owner's part |
|---|---|---|
| 1 | Phase 1 (git/GitHub/docs) + Phase 2 (installs, Laravel running) | Runs installs with agent |
| 2 | Phase 3 — database + all 64 menu items seeded | Glance + 2 commands |
| 3–4 | Phase 4 — Blade + SASS conversion | Day 4: visual side-by-side check |
| 5 | Phase 5 — order API complete | 5-min check |
| 6 | Phase 6 — frontend wired, **first real order works** | Test incl. on phone |
| 7–8 | Phase 7 — full admin (2FA, orders, menu, settings) | Create account, test flow |
| 9 | Phase 8-min — security essentials, core tests, README | Nothing |
| 10 | Fixes + launch checklist | Bank details in Settings, final review |

**Slip rule:** anything that overruns goes to the v1.1 list — only Phases 1–7 core + security
essentials can block launch. **Learning debt is repaid** in a post-launch study week using
`LEARNING.md` (the glossary is written completely as the build proceeds).

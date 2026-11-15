# CitrusSplash — Architecture Essentials
The rules that must not break, no matter what. If any later idea conflicts with this file,
**this file wins**. Each rule gets a phase where it's taught — the owner must be able to
explain every rule by launch.

---

## 1. Security non-negotiables

| # | Rule | Taught in |
|---|---|---|
| S1 | **Never trust the browser.** The server re-prices every order from the DB and re-validates every field, even though the frontend validates too | 5 |
| S2 | **Escape all output.** Blade `{{ }}` escapes automatically. Any JS-built HTML must go through the shared `esc()` helper — item names/settings will one day contain user-typed text | 8 |
| S3 | **Secrets live only in `.env`** (Turnstile keys, app key, DB creds). `.env` and `*.sqlite` are gitignored **before the first push**. Nothing secret is ever committed | 1 |
| S4 | **Passwords: bcrypt only** (Laravel default). Never stored or logged in plain text | 7 |
| S5 | **Admin = middleware gate + password + TOTP + lockout.** No admin route exists outside the gate | 7 |
| S6 | **Rate-limit every public endpoint** (orders 5/min/IP, tracking 10/min/IP, login 5/15min) | 8 |
| S7 | **CSRF protection stays on** for all web forms (Laravel default — we never disable it) | 5 |
| S8 | **SQL: Eloquent/query builder only.** Never glue user input into raw SQL strings | 5 |
| S9 | **Turnstile on order creation** (public, bot-facing). Admin login relies on lockout + TOTP — Turnstile there is optional and **skipped in v1** | 8 |
| S10 | **Generic errors to users, details to logs.** ("Can't find that order" — never "no such order for that phone") | 5 |
| S11 | **Audit everything sensitive:** logins, status changes, payment confirmations, menu/settings edits | 7 |
| S12 | Admin pages send `noindex`; site enforces HTTPS when deployed | 7/8 |

## 2. Data rules

| # | Rule |
|---|---|
| D1 | Money is stored as **integers in naira** (matches existing data; if Paystack arrives later, convert ×100 at the gateway boundary only) |
| D2 | Order numbers are **server-issued and sequential** (`CS-` + padded id). The browser never generates them |
| D3 | Orders are **snapshots**: item name + price are copied into `order_items` at creation. Later menu edits never rewrite history |
| D4 | Only `status`, `payment_status`, `cancel_reason`, `payment_confirmed_at` may change after creation — always via admin actions, always audit-logged |
| D5 | **Settings table is the only home for store-tunable values** (bank details, fees, ETAs, templates, auto-cancel minutes). No hardcoded business values in code |
| D6 | Timezone is **Africa/Lagos** for storage display and business logic |
| D7 | Orders cannot advance past "Confirmed" until **payment is confirmed** by staff (kitchen rule) |
| D8 | A timed-out-cancelled order is restorable; a staff-cancelled order is final |

## 3. Frontend rules

| # | Rule |
|---|---|
| F1 | No CSS/JS frameworks (no Bootstrap, no Tailwind). SASS + vanilla JS only |
| F2 | Blade layout holds shared markup **once** (header, nav, footer, drawer, toast). No page-level copies |
| F3 | localStorage holds **the cart only** — never orders, never status. The DB is the truth |
| F4 | All `fetch()` calls have error handling with visible, friendly fallbacks (and a retry hint) |
| F5 | Every animation respects `prefers-reduced-motion` (fix the current gap in `depth.js`/tilt) |
| F6 | One shared `esc()` helper for any interpolated HTML in JS |
| F7 | Visual output must remain identical to the approved prototype — SASS reorganization is structural, not a redesign |
| F8 | **Humanized markup:** divs + descriptive class names in the prototype's existing spirit (`.card`, `.hero-title`, `.cart-btn` — named for *meaning*, not generated soup); consistent indentation; a one-line comment opening each Blade section/partial. Code is written for a human to read |

## 4. Code style

- **PSR-12** via Laravel Pint; run before commits
- Small, intention-named methods; no magic numbers — use constants/config/settings
- Validation in FormRequest classes, not inline in controllers
- Comments written for a learner: explain *why*, not just *what*; comment density decreases as the owner grows
- New terms appear in `LEARNING.md` (repo root) the same day they're introduced

## 5. Git conventions

- Branch: `main` only (single-developer simplicity)
- Commits: small, frequent, conventional prefixes — `feat:`, `fix:`, `docs:`, `style:`, `test:`, `chore:`
- A commit at the end of **every mini-milestone** (not just phases)
- Never commit: `.env`, `*.sqlite`, `node_modules/`, `vendor/`, build output
- The prototype is **never committed** — it lives locally in `design-reference/` (the visual
  blueprint, kept out of git via `.gitignore`); the repo holds only the real application

## 6. Testing essentials (Phase 8, but tests are written alongside backend phases)

Minimum feature tests (Pest or PHPUnit):
1. Order creation: valid order persists with server-re-priced totals
2. Order creation: tampered client prices are ignored (DB price wins)
3. Order creation: invalid phone / missing address (delivery) rejected
4. Tracking: correct number+phone returns order; wrong phone returns generic 404
5. Login: 5 bad attempts lock the account+IP for 15 min
6. Status flow: cannot reach "Preparing" while payment is pending
7. Auto-cancel job: unpaid order older than N minutes becomes cancelled & restorable

## 7. Environment requirements (owner's Windows machine)

| Tool | Version | Used for |
|---|---|---|
| PHP | 8.3+ | Backend |
| Composer | 2.x | PHP packages |
| Node.js | 20 LTS | SASS/Vite build |
| Git | latest | Version control |
| VS Code | latest | Editor (+ extensions: PHP Intelephense, Laravel Blade, Error Lens, GitLens, DotENV) |
| (optional) Laragon | — | All-in-one PHP/MySQL environment if manual installs fight back |

Run the app: `php artisan serve` (owner-typed) + `npm run dev` (Vite watch).
Test on a real phone: `php artisan serve --host=0.0.0.0`, then open the PC's LAN IP on the
owner's Android — a Phase 6 ritual, every time UI changes.

## 8. Beginner contract (binding — details in Agent.md)

1. No code enters the repo unexplained
2. One new concept per step; jargon → `LEARNING.md`
3. Mini-task after each step (owner thinks/edits; agent verifies)
4. Owner runs terminal commands dictated step-by-step; agent takes over on failure and returns the wheel
5. Bugs are lessons: read the error → owner guesses → test the guess
6. Every phase ends with something visible working in the browser

# CitrusSplash — Architecture

Companion to `PRD.md`. Describes how the system is put together. Beginner-friendly by design:
every section should be explainable by the owner after its phase.

---

## 1. The shape: one Laravel monolith

One Laravel application does everything:

```
                    ┌──────────────────────────────────────────┐
 Browser ──────────▶│  Laravel (PHP 8.3)                       │
                    │                                          │
  • customer pages  │  routes ─▶ middleware ─▶ controllers     │
  • admin panel     │                 │                        │
  • JSON API        │        ┌────────┴─────────┐              │
                    │        ▼                  ▼              │
                    │   Blade views        Eloquent models     │
                    │   (HTML pages)       (SQLite/MySQL DB)   │
                    └──────────────────────────────────────────┘
```

**Why a monolith:** one codebase, one deploy, one mental model — ideal for a first full-stack
project and a single-location restaurant. No microservices, no SPA framework.

### Request lifecycle (the concept to internalize in Phase 2)

1. Browser requests `GET /menu`
2. `public/index.php` boots Laravel
3. Router matches the URL to a **route** in `routes/web.php`
4. **Middleware** runs (e.g., "is this an admin route? then demand a staff session")
5. A **controller** method runs — it may ask **models** (Eloquent) for data
6. The controller returns a **Blade view** (HTML) or **JSON** (API)
7. Browser receives the response

## 2. Frontend architecture (Blade + SASS + vanilla JS)

| Concern | Decision |
|---|---|
| Templates | Blade. `resources/views/layouts/app.blade.php` holds header/nav/footer/cart-drawer/toast **once**; pages extend it. Kills the 8-file copy-paste problem |
| Mapping | Each existing HTML page maps 1:1 to a Blade view (`home`, `menu`, `checkout`, `status`, `about`, `privacy`, `terms`) + `admin/*` views |
| Styling | Existing 938-line `style.css` is reorganized into **SASS partials** (`resources/sass/…`), compiled by **Vite** → `public/build`. Visual output unchanged — see `DBD.md` for the file map |
| JS | Existing per-page vanilla JS is kept, moved to `resources/js/`, bundled by Vite. `main.js` remains the shared cart/drawer/toast module. Only `checkout.js` and `status.js` learn `fetch()` in Phase 6 |
| Cart | **localStorage only** — client convenience. The database is the sole source of truth for orders |
| Menu data | The old static `js/menu-data.js` is replaced by a **dynamic endpoint that returns the identical JSON shape** from the DB (`GET /js/menu-data.js`). Page scripts notice nothing |

## 3. Backend architecture

### Routes
- `routes/web.php` — Blade pages (customer), legal pages, admin (session-guarded)
- `routes/api.php` — public order API (throttled, Turnstile-verified)

### Controllers (kept deliberately small)
| Controller | Job |
|---|---|
| `PageController` | Customer Blade pages |
| `MenuDataController` | Serves dynamic `/js/menu-data.js` from DB |
| `Api\OrderController` | `store()` create order · `show()` tracking lookup |
| `Admin\AuthController` | Login (password → TOTP step), logout |
| `Admin\DashboardController` | Orders table + status filters + phone/name search + detail |
| `Admin\OrderActionsController` | Status change, payment confirm, restore timed-out order |
| `Admin\MenuController` | Categories + items CRUD |
| `Admin\SettingsController` | Settings form |
| `Admin\AuditController` | Audit log view (owner) |

### Models (Eloquent)
`Category` · `MenuItem` · `Order` · `OrderItem` · `Setting` · `User` (staff) · `AuditLog`

### Order numbers (collision-free by construction)
`order.no = "CS-" + zero-padded auto-increment id` (e.g. id 1 → `CS-0001`, id 999 → `CS-0999`).
Created inside a DB **transaction** together with re-priced line items — no random numbers,
no races, trivially explainable.

### Validation
FormRequest classes (`StoreOrderRequest`). The **server re-prices every order** from the DB and
ignores any prices the browser claims. See `BSD.md` §Validation.

### Scheduled job (the only background work in v1)
`everyFiveMinutes → CancelStaleUnpaidOrders`:
unpaid orders older than `settings.auto_cancel_minutes` (default 60) → `status=cancelled`,
`cancel_reason=payment_timeout` → audit-logged → **restorable by staff**.

## 4. Admin authentication architecture (difficult by design)

```
GET /admin/*  ──▶  StaffAuth middleware
                     │ no valid session? ──▶ redirect /admin/login
                     ▼
   Login form: password + TOTP 6-digit code
                     │
        5 failed attempts / 15 min (account+IP) ──▶ lockout
                     │ ok
                     ▼
        session (60-min idle timeout, secure cookie) ──▶ admin panel
                     │
        every login / status change / edit ──▶ audit_logs
```

- **No self-registration.** Accounts exist only via `php artisan staff:create` (owner-run).
- **Recovery:** `php artisan staff:reset {email}` (owner-only) regenerates TOTP if a staff phone is lost.
- Passwords **bcrypt**-hashed; TOTP secrets **encrypted at rest**.
- Session idle timeout enforced by checking `last_activity` in middleware.
- Admin pages send `noindex` headers. URL stays `/admin` (protection = middleware, not secrecy).

## 5. API surface

| Method | URI | Auth | Purpose |
|---|---|---|---|
| POST | `/api/orders` | public (Turnstile + 5/min/IP) | Create order → returns order no + bank details |
| GET | `/api/orders/{no}?phone=` | public (10/min/IP) | Tracking snapshot (statusIdx, countdown) |
| GET | `/js/menu-data.js` | public | Dynamic menu, same JSON shape as the old static file |
| GET/POST | `/admin/login`, `/admin/logout` | session | Staff auth |
| GET | `/admin` | staff | Orders dashboard |
| PATCH | `/admin/orders/{order}/status` | staff | Advance/cancel status |
| POST | `/admin/orders/{order}/payment-confirm` | staff | Mark transfer received |
| POST | `/admin/orders/{order}/restore` | staff | Undo a payment-timeout cancel |
| GET/POST | `/admin/orders/create` | staff | Enter phone/walk-in orders (FR-15) |
| CRUD | `/admin/menu/*`, `/admin/settings`, `/admin/audit` | staff | Management screens |

JSON contract examples live in `BSD.md` §API.

## 6. Data layer

- **Dev:** SQLite (`database/database.sqlite`) — zero setup.
- **Later hosting:** migrations are written MySQL-portable from day one (no SQLite-only columns).
- Schema, indexes, models, validation and seeding: **`BSD.md` is authoritative**.

## 7. Cross-cutting decisions

| Topic | Decision |
|---|---|
| Timezone | `config.app.timezone = Africa/Lagos` — set in Phase 2, never UTC in UI |
| Money | Integers, naira (matches existing data + JS formatter `₦ + toLocaleString`) |
| Errors | Friendly generic messages to users; real detail to log files |
| Rate limits | Orders 5/min/IP · tracking 10/min/IP · login 5/15min per account+IP |
| Security headers | X-Frame-Options, X-Content-Type-Options, Referrer-Policy — small middleware (Phase 8). **Real CSP deferred to v2** (it fights Vite inline assets; not worth beginner pain) |
| Deploy posture | **GitHub only for now** (owner decision). Later: cPanel shared hosting (PHP+MySQL) is the natural target — Laravel runs there; HTTPS enforced at deploy time |
| Git | `main` branch; conventional commits (`feat:`, `fix:`, `docs:`…); `.env` and `*.sqlite` gitignored from Phase 1 |

## 8. Where the existing files go (Phase 2/4)

| Today | Becomes |
|---|---|
| `index.html`, `menu.html`, `checkout.html`, `status.html`, `about.html` | Blade views in `resources/views/` |
| `admin/*.html` | Blade views in `resources/views/admin/` (wrapped in real auth) |
| `css/style.css` | SASS partials in `resources/sass/` (same output) |
| `admin/css/admin.css` | `resources/sass/admin.scss` |
| `js/main.js`, `menu.js`, `about.js`, `depth.js` | `resources/js/` (bundled, behavior unchanged) |
| `js/checkout.js`, `status.js` | Same + `fetch()` to the API (Phase 6) |
| `js/menu-data.js` | Seeded DB rows + dynamic endpoint |
| `images/*` | `public/images/` |
| `menu 1.jpg`, `menu 2.jpg` | Kept as transcription reference in `design-reference/` |

# CitrusSplash — Backend Schema Document (BSD)
Authoritative database design. Money = integers in **naira**. Timezone = **Africa/Lagos**.
Migrations must stay MySQL-portable (no SQLite-only column types) so future cPanel hosting is a
config change, not a rewrite.

---

## 1. Tables

### `categories`
| Column | Type | Notes |
|---|---|---|
| id | increments | |
| slug | string unique | `smoothies`, `sandwiches`, `salads` |
| label | string | "Smoothies", "Sandwiches", "Salads & More" |
| emoji | string | "🍊", "🥪", "🥗" |
| sort_order | integer | display order |
| timestamps | | |

### `menu_items`
| Column | Type | Notes |
|---|---|---|
| id | increments | |
| category_id | FK → categories | |
| slug | string unique | e.g. `abuja-tonic` |
| name | string | |
| description | text nullable | ingredients line |
| price | integer | **naira**, e.g. 4550 |
| emoji | string | visual tile |
| tag | enum: `pop`·`new`·`fresh`·null | badge |
| is_available | boolean default true | sold-out toggle |
| sort_order | integer | position within category |
| timestamps | | |

### `orders`
| Column | Type | Notes |
|---|---|---|
| id | increments | order no derives from this |
| no | string unique index | `CS-` + zero-padded id, e.g. `CS-0042` |
| customer_name | string | |
| phone | string index | normalized `0XXXXXXXXXX` |
| address | text nullable | required if delivery |
| type | enum: `pickup`·`delivery` | |
| source | enum: `web`·`phone` | how the order entered (web = checkout page; phone = staff-entered, FR-15) |
| payment_method | enum: `transfer` | only value in v1 |
| payment_status | enum: `pending`·`confirmed` | default `pending` |
| status | enum: `received`·`confirmed`·`preparing`·`ready`·`out_for_delivery`·`completed`·`cancelled` | default `received` |
| cancel_reason | enum: `manual`·`payment_timeout`·null | |
| subtotal | integer | naira |
| fee | integer | naira (0 for pickup) |
| total | integer | naira |
| item_count | integer | denormalized for table display |
| payment_confirmed_at | datetime nullable | |
| placed_at | datetime | = created_at (kept explicit for clarity) |
| timestamps | | |

**Indexes:** `no` (unique), `phone`, `status`, `(payment_status, placed_at)` for the timeout job.

### `order_items`
| Column | Type | Notes |
|---|---|---|
| id | increments | |
| order_id | FK → orders | |
| menu_item_id | FK → menu_items nullable | snapshot; item may be deleted later |
| name | string | **snapshot** at order time |
| price | integer | **snapshot** at order time |
| qty | integer | |

### `settings` (key/value — the single home for store-tunable values)
| Column | Type |
|---|---|
| id | increments |
| key | string unique |
| value | text |
| timestamps | |

**Seeded keys & defaults** (all editable in admin Settings):
| Key | Default |
|---|---|
| `bank_name` | `___` (store homework) |
| `bank_account_number` | `___` |
| `bank_account_name` | `___` |
| `delivery_fee` | `1500` ⚠️ placeholder — store must confirm |
| `pickup_eta` | `Ready in ~15–20 min` |
| `delivery_eta` | `Arrives in ~30–45 min` |
| `store_phones` | `0817 153 9888, 0907 982 1327, 0704 439 1134` |
| `whatsapp_ready_pickup` | `"Hi {name}! 🧺 Your CitrusSplash order {no} is ready for pickup at Stick & Stone Plaza, Wuse. Total {total}. See you soon!"` |
| `whatsapp_ready_delivery` | same with rider wording |
| `auto_cancel_minutes` | `60` |

Template placeholders available: `{name}`, `{no}`, `{total}`, `{type}`, `{eta}`.

### `users` (staff)
| Column | Type | Notes |
|---|---|---|
| id | increments | |
| name | string | |
| email | string unique | login handle |
| password | string | **bcrypt** |
| role | enum: `owner`·`staff` | owner sees audit log + can manage staff |
| totp_secret | text nullable | **encrypted at rest** |
| totp_enabled | boolean default false | true after QR pairing |
| last_login_at | datetime nullable | |
| timestamps | | |

Created **only** via `php artisan staff:create` (owner-run; prints QR for the authenticator app).
TOTP recovery: `php artisan staff:reset {email}` regenerates the secret if a staff phone is lost.
No registration route exists.

### `audit_logs`
| Column | Type |
|---|---|
| id | increments |
| user_id | FK → users nullable |
| action | string — `login.success`·`login.failed`·`order.status_changed`·`order.payment_confirmed`·`order.restored`·`menu.updated`·`settings.updated` |
| details | JSON — e.g. `{"from":"received","to":"confirmed"}` |
| ip_address | string |
| user_agent | string |
| created_at | timestamp |

---

## 2. Relationships

```
categories 1───* menu_items
orders     1───* order_items *───1 menu_items (nullable, snapshot)
users      1───* audit_logs
settings   standalone key/value
```

## 3. Eloquent models & key behavior

| Model | Notes |
|---|---|
| `Category` | `items()` hasMany |
| `MenuItem` | `category()` belongsTo; scope `available()` |
| `Order` | `no` generated on create inside the same transaction; `lines()` hasMany; status transitions validated in one place (model or dedicated action class) |
| `OrderItem` | belongsTo order |
| `Setting` | static `Setting::get(key, default)` helper with light caching |
| `User` | staff; `isOwner()` helper |
| `AuditLog` | created via an `Audit::log()` helper (never by hand) |

## 4. Validation rules (`StoreOrderRequest`)

| Field | Rules |
|---|---|
| name | required, string, min:2, max:80 |
| phone | required, normalized (strip non-digits, `234→0`), regex `^0[789][01]\d{8}$` |
| type | required, in: pickup, delivery |
| address | required_if type=delivery, min:6 |
| items | required, array, min:1 — each: `id` exists + available in menu_items, `qty` integer 1–20 |
| turnstile token | verified server-side against Cloudflare (Phase 8; until then, throttle only) |

**Server re-pricing:** totals are computed **only** from DB prices:
`subtotal = Σ menu_items.price × qty` (+ `delivery_fee` from Settings if delivery).
Client-supplied prices are never read.

## 5. API contracts

### `POST /api/orders`
Request:
```json
{
  "name": "Adaeze Okafor",
  "phone": "08171539888",
  "type": "delivery",
  "address": "Plot 4, Buchanan Crescent, Wuse 2",
  "items": [ { "id": "abuja-tonic", "qty": 2 }, { "id": "club", "qty": 1 } ]
}
```
Response `201`:
```json
{
  "no": "CS-0042",
  "type": "delivery",
  "lines": [
    { "id": "abuja-tonic", "name": "Abuja Tonic", "price": 4550, "qty": 2 },
    { "id": "club", "name": "Club Sandwich", "price": 5200, "qty": 1 }
  ],
  "sub": 14300, "fee": 1500, "total": 15800,
  "eta": "Arrives in ~30–45 min",
  "bank": { "name": "___", "account_number": "___", "account_name": "___" },
  "status": "received", "payment_status": "pending"
}
```
Errors: `422` per-field messages · `429` rate limit · Turnstile failure (Phase 8).

### `GET /api/orders/{no}?phone=08171539888`
Response `200`:
```json
{
  "no": "CS-0042", "status": "preparing", "statusIdx": 2,
  "steps": ["Received","Confirmed","Preparing","Ready for Pickup","Completed"],
  "payment_status": "confirmed",
  "type": "delivery",
  "lines": [ { } ], "sub": 14300, "fee": 1500, "total": 15800,
  "placed_at": "2026-11-15T12:04:00+01:00",
  "eta": "Arrives in ~30–45 min",
  "auto_cancel_in_minutes": null
}
```
`statusIdx` is 0–4 for the tracker. Cancelled orders return `status: "cancelled"` + reason.
`auto_cancel_in_minutes` is a number **only** while `payment_status = pending` (powers the
countdown in the tracker). Wrong phone / unknown no → `404` with the generic friendly message.

### `GET /js/menu-data.js`
Returns `window.CS_MENU = { cats: [...], items: [...] }` **identical in shape** to the current
static file, built from `categories` + `menu_items` (respecting `is_available`), served as
`application/javascript`. Existing page scripts require zero changes.

## 6. Seeding plan

| Seeder | Content |
|---|---|
| `CategorySeeder` | the 3 real categories |
| `MenuItemSeeder` | all items transcribed from `js/menu-data.js` (49 smoothies, 11 sandwiches, 4 salads) — script-assisted conversion, then owner spot-checks prices in admin |
| `SettingSeeder` | every key from §Settings with defaults |
| `DatabaseSeeder` | calls the above; staff accounts are **not** seeded — created via `php artisan staff:create` |

## 7. Migration order
`categories → menu_items → settings → users(+totp columns) → orders → order_items → audit_logs`
(FK-safe order; each migration explained to the owner as it's written — Phase 3.)

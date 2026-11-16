# CitrusSplash — Design Brief Document (DBD)
The design is **already approved** — this document protects it. The build reorganizes how the
design is *coded* (Blade + SASS), never how it *looks*.

---

## 1. Brand

| | |
|---|---|
| Name | Citrus Splash & Restaurant |
| Place | Suite A105, Stick and Stone Plaza, Wuse, Abuja |
| Idea | "Eat clean, fresh & delicious" — pressed, tossed and toasted **when you order**, never sitting around |
| Promise | No login, no friction, no waiting to find out — order in under a minute |
| Voice | Warm, playful, direct. Nigerian English. Emoji used as seasoning, not as the meal ("🧺 Your basket", "🍊 Made fresh daily") |
| Logo mark | Hand-drawn orange-slice SVG (inline in the header/footer — moves to a Blade partial) |

## 2. Visual identity (extract these into `_variables.scss` in Phase 4)

| Token | Value / source | Use |
|---|---|---|
| `--orange` | #F5821F | Primary brand, CTAs, accents |
| `--ink` | #2B2B2B | Text, footer background, outlines |
| `--green` | #4C9A2A | "Fresh" accents, salad lane |
| `--yellow` | (pull exact value from `style.css`) | Highlights, stickers, badges |
| Paper/cream background | (pull exact value) | Page background |
| `--muted` | (pull exact value) | Secondary text |
| Category hues | juice / salad / sand tile tints | Card visuals, chips, line-emoji tiles |

*(Exact values are read from the existing `css/style.css` during Phase 4 — the CSS is the source
of truth, not memory.)*

## 3. Typography

| Face | Weights | Role |
|---|---|---|
| **Baloo 2** | 600 / 700 / 800 | Display: headlines, prices, big numbers (rounded, friendly) |
| **Inter** | 400 / 500 / 600 / 700 | Body, UI, forms |

Loaded via Google Fonts (`preconnect` + single CSS request) — kept as the only external asset.

## 4. Shape & motif language

- Big rounded cards and pill buttons; coin-circle food tiles
- Hand-drawn squiggle underlines (inline SVG), sticker tags ("no login · just order")
- Halftone dot overlays on category tiles
- The scrolling marquee strip on the hero
- Wave divider into the dark footer

## 5. Motion inventory (all must respect `prefers-reduced-motion`)

| Motion | Where | File |
|---|---|---|
| Reveal-on-scroll (fade/rise) | Sections, cards | `main.js` (IntersectionObserver) |
| Depth parallax | Hero orange scene | `depth.js` |
| Card tilt on hover | Cards, category tiles | inline `data-tilt` handlers |
| Fly-to-cart emoji arc | Every Add button | `main.js fly()` (already reduced-motion aware) |
| Drawer slide + scrim | Basket | `main.js` |
| Badge bump + toast | Add feedback | `main.js` |
| Marquee scroll | Hero strip | CSS |

**Known gap to fix in Phase 4:** `depth.js` and the tilt handlers don't check
`prefers-reduced-motion` yet — add the check (Architecture-essentials F5).

## 6. Component inventory (preserve as-is, port to Blade/SASS)

Customer: site header + desktop nav + mobile nav · cart button & count badge · cart drawer +
scrim · toast · hero (+ orange scene, dish coins, price tag) · category tiles · menu item cards
(emoji visual tile, category chip, badge, price + ready-time, Add button) · sticky tab bar ·
search field · result count line · empty states · checkout form cards (step labels, pick cards,
field errors) · order summary card · success panel + order number display + bank-details card ·
tracker stage hero + vertical timeline · payment recap · footer (+ wave, link grid, base strip).

Admin (keep the existing `admin.css` look): dark sidebar + brand block · topbar · status pill
filters · table grid + expandable detail rows · status tags · status dropdown · toast.

## 7. Design rules

1. **No redesign.** If a screen looks different from the prototype at Phase 4 review, that's a bug
2. All visual tokens live **only** in `_variables.scss` — no hard-coded hex values in partials
3. Mobile-first; keep the existing breakpoints and the existing mobile nav pattern
4. Accessibility keeps its current standard: aria labels, focus-visible, keyboard Escape closes
   drawer, semantic landmarks — and gains the reduced-motion fixes
5. One partial per coherent chunk (see §8); names describe the screen/chunk, not the framework
6. Admin keeps its separate `admin.scss` entry (own palette, same token discipline)
7. **Humanized markup** (Architecture-essentials F8): divs + descriptive classes like the
   prototype, tidy indentation, a short comment at the top of every Blade section — readable
   by the owner first, by the browser second

## 8. SASS file map (target structure)

```
resources/sass/
  style.scss          ← imports everything below (one entry point)
  admin.scss          ← admin entry (imports _admin partials)
  _variables.scss     ← color/type/spacing tokens (single source)
  _mixins.scss        ← reduced-motion, breakpoints, halftone…
  _base.scss          ← reset, body, container, buttons, pills, fields
  _typography.scss
  _header.scss        ← header, navs, cart button
  _hero.scss          ← hero, orange scene, marquee
  _home.scss          ← categories, featured, steps, about/visit
  _menu.scss          ← tabs, cards, search, count line, price note
  _drawer.scss        ← cart drawer, scrim, line items, steppers
  _checkout.scss      ← form cards, pick cards, summary, success, bank card
  _status.scss        ← stage hero, timeline, recap
  _about.scss
  _footer.scss        ← wave, links, base
  _toast.scss         ← toasts, fly-dot, misc effects
  _admin/*.scss       ← sidebar, topbar, table, tags, pills, forms
```

## 9. Imagery

- Existing: `orange-ref.png` (hero), `smoothie.jpg`, `salad.jpg`, `club-sandwich.jpg` (dish coins)
- All 64 menu items use **emoji visual tiles** in v1 (no per-item photos — v2 shelf)
- `menu 1.jpg` / `menu 2.jpg` remain the transcription reference (in `design-reference/`)
- `citrussplash.jpeg` stays as brand reference in `design-reference/`

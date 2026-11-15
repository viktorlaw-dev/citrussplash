# Agent.md — How the Building Agent Must Work
This file binds the AI agent (and any human helper) during the build of CitrusSplash.
Read it — plus the other 7 plan documents — at the start of every session.

**Canonical location of the plan documents: the project folder, `docs/` subfolder**
(e.g. `...\Desktop\03_Projects\PROJECTS\CitrusSplash project\docs\`) — copied there as the very
first step of Phase 1 so they travel with the repo on GitHub. Until Phase 1, the working copies
live in `C:\Users\Victor Lawrence\.opencode\plan`.
(PR D · Architecture · Architecture-essentials · APP flow · DBD · BSD · Agent · openCode)

---

## 1. Identity & mission

You are a **guide-builder**. The owner (Victor) is a beginner learning full-stack development by
building this real product. Your job is to deliver the app *and* grow the owner's understanding —
in that order of equality. Shipping unexplained code is a failed session, even if it works.

## 2. The learning contract (binding)

1. **Explain before code.** Every step starts with 2–3 plain sentences: what + why
2. **One new concept per step.** Every new term gets a one-line plain-English definition **and**
   is added to `LEARNING.md` (repo root) the same day
3. **No unexplained code enters the repo.** Small files, generous comments early, lighter later
4. **Mini-task after each step** — owner thinks/edits in VS Code; agent verifies and explains the result
5. **Owner runs the terminal.** Agent dictates ONE command at a time with an explanation;
   owner pastes the output back. On failure: agent diagnoses, fixes — or takes over directly
   (agent works on the owner's machine) — then hands the wheel back
6. **Bugs are lessons.** Read the error together → owner guesses the cause → test the guess.
   Never fix silently
7. **Every phase ends with something visible** working in the browser + a recap + a commit
8. Ask before assuming. If a decision isn't in the plan documents, ask the owner

> **⏱️ 10-day mode (LOCKED — see PRD §9):** the deadline overrides live pairing. The agent does
> the heavy building between owner check-ins; the owner commits ~1 hr/day (run commands, test,
> decide). Explanations are written into `LEARNING.md` and inline comments as the code lands;
> mini-tasks are limited to one short task every ~2 days; a dedicated study/teach-back week
> follows launch. Never skip writing the glossary — it is the learning debt repayment.

## 3. Terminal protocol

- One step at a time; explain what it does before the owner runs it
- Owner pastes **full error text** (never summaries)
- ⚠️ flag + plain-English warning before any destructive command
- Agent may verify state itself at any time (read-only checks: file reads, `git status`, logs)

## 4. Phase order & Definition of Done

| Phase | Work | Done when |
|---|---|---|
| **1. Git + GitHub** | init repo, `.gitignore` (incl. `.env`, `*.sqlite`) BEFORE first push, **copy the 8 plan docs + `architecture-diagram.svg` into `docs/`**, push prototype to owner's GitHub, create `LEARNING.md` starter glossary | Owner's GitHub shows the prototype + `docs/`; owner ran every git command themselves |
| **2. Laravel + setup** | PHP 8.3/Composer/Node installed (owner-typed, agent-guided); `laravel new` in place; timezone Africa/Lagos; folder tour given; VS Code + 5 extensions configured; app runs via `php artisan serve` | Owner can name every top-level folder's job and see the default page |
| **3. Database** | Migrations + models per BSD.md; seeders (categories, 86 menu items from `js/menu-data.js`, settings); tinker demo | `php artisan migrate --seed` runs clean; owner queries items via tinker |
| **4. Blade + SASS** | Layout + partials; all pages ported 1:1; style.css → SASS partials (visual output identical); depth/tilt reduced-motion fix; privacy/terms pages; status-link params bug fixed; side-by-side visual review with owner | Owner compares prototype vs Blade build — no visual differences |
| **5. Order API** | `POST /api/orders` (re-pricing, sequential CS- numbers, transaction, validation), tracking GET, dynamic `/js/menu-data.js`; tests from Architecture-essentials §6 written alongside | API verified via tinker/curl; tampered-price test passes |
| **6. Wire frontend** | `checkout.js` → real API (success panel shows real bank details + CS no); `status.js` → tracking endpoint + countdown; error states | Owner places a real order end-to-end locally **and on their real phone via LAN** |
| **7. Admin** | Staff auth (login → TOTP → lockout) + `staff:reset` recovery command, live orders table (chime + auto-refresh + phone/name search), status changes + payment confirmation (gate per D7), restore, manual phone-order entry (FR-15), WhatsApp tap-to-send buttons, menu CRUD, settings, audit log, auto-cancel scheduler | Owner creates a staff account and runs a full order lifecycle in admin — including entering a phone order |
| **8. Hardening** | `esc()` sweep in JS templates, rate limits, security headers (no CSP in v1), Turnstile live on order creation, full test suite green, README, final push | All Architecture-essentials rules verifiably hold |

Phases are sequential; a phase may be paused and resumed — the plan documents are the memory.

## 5. Security non-negotiables
Enforce `Architecture-essentials.md` at all times. When in doubt, that file wins over any
convenience. Key reminders: server re-prices; secrets only in `.env`; escape all JS-built HTML;
rate limits; audit sensitive actions; admin never outside the middleware gate.

## 6. Do-nots

- No Bootstrap/Tailwind/React/Vue — the stack is decided (Blade + SASS + vanilla JS + Laravel)
- No hosting purchases or DNS changes without the owner
- No placeholder business values hardcoded in code — they live in Settings
- No scope creep: v2 shelf items (PRD §5) stay unbuilt until the owner pulls them
- Don't refactor beyond the current phase's scope
- Never commit `.env`, `*.sqlite`, `node_modules/`, `vendor/`
- Never leave a step unexplained to save time — slow down instead

## 7. Session rhythm

**Start:** read the 8 plan docs + `git status` + `LEARNING.md` tail → confirm current phase with owner.
**During:** say → show → run → explain → try → commit.
**End:** recap (what/why/how), glossary updated, commit pushed, preview the next step.

## 8. Conflict resolution
PRD wins on *what* to build · Architecture-essentials wins on *how it must behave safely* ·
BSD wins on data · DBD wins on looks · Agent.md wins on *how we work together*.

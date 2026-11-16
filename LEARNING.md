# 📚 LEARNING.md — CitrusSplash Glossary
Your personal textbook for this exact project. Every new term we meet gets a plain-English
entry here, the same day. Read it any time; quiz yourself before each session.

---

## Day 1 — Git & version control

**Git** — software that records snapshots of your project. Not "the cloud" — it lives on your PC.
**Repository (repo)** — a project folder that Git is tracking. Created by `git init`. It stores
its memory in a hidden `.git/` folder inside the project.
**Commit** — one saved snapshot ("checkpoint") of your files, with a message describing it.
Unlike Ctrl+S, a commit is permanent history you can always go back to.
**Staging (`git add`)** — choosing *which* changes go into the next snapshot before you take it.
**`.gitignore`** — a list of files Git must never track (like `.env`, which holds secrets).
Rule S3: secrets never leave your PC.
**Remote** — a copy of the repo living elsewhere (for us: GitHub). You **push** snapshots to it.
**Push** — uploading your commits to the remote (GitHub). This is your off-site backup.
**GitHub** — a website that stores Git repos. Ours holds the code; it is not the app's host.
**`git status`** — asks git "what do you see right now?" Run it any time — it never changes anything.
**`git add -A`** — stage *all* changes (new, edited, deleted) for the next snapshot. Staging is
loading the camera, not pressing the shutter — nothing is saved until `git commit`.
**`origin`** — git's conventional nickname for the repo's home address on GitHub
(`git remote add origin <url>` teaches git where to push).
**Token (PAT)** — GitHub no longer accepts account *passwords* for pushes. Identity is proven
with a long random "personal access token". Git Credential Manager creates one automatically
after the browser sign-in (that popup we completed) and stores it safely — future pushes
happen silently.

## Day 1 (continued) — the build tools

**PHP** — the language Laravel is written in: the engine that runs the server. Ours lives in
`C:\php` (v8.3.36), configured by `C:\php\php.ini`.
**Extension** — a plug-in that gives PHP a skill. We enabled 9 in `php.ini`: `pdo_sqlite`
(talk to the database), `mbstring` (text in any language), `openssl` (secure downloads),
`curl` (fetch web resources), `gd` (images), `zip`, `intl`, `fileinfo`, `sqlite3`.
**Composer** — PHP's package installer: downloads Laravel and all its parts from the internet
into `vendor/`. That's why `vendor/` is gitignored — rebuilt any time with one command,
never stored in git.
**Node.js / npm** — the JavaScript runtime + its package installer. Later (Phase 4) npm
compiles our Bootstrap/SASS into the final CSS the browser receives, via a tool called Vite.
**PATH** — Windows' list of folders to search when you type a command. If `php -v` says
"not recognized", the php folder isn't on the PATH. Gotcha: after changing the PATH, only
*newly opened* terminals see the change — old windows keep the old list.

## Day 2 — The database

**Laravel** — the PHP framework: pre-built, secure plumbing for web apps (routing, database,
validation, sessions — wired and battle-tested, so we build the restaurant, not the plumbing).
**Artisan** — Laravel's command-line helper: `php artisan migrate`, `php artisan serve`, …
**Migration** — a PHP file that creates or changes one table's structure, in repeatable steps.
`php artisan migrate` runs pending ones; the filename timestamp decides the run order.
**Table** — one grid of related data. `menu_items` is 64 rows with identical columns — a very
tidy spreadsheet.
**Column types we used** — `string` (short text) · `text` (long text) · `integer` (whole
numbers — ALL money, in naira, never decimals) · `boolean` (true/false) · `enum` (value from
a fixed list — status can only ever be one of 7 words) · `nullable` (may be empty) ·
`default` (value filled in automatically when none given).
**Foreign key (FK)** — a column pointing at another table's row: `menu_items.category_id`
→ `categories.id` is the link behind "49 items belong to Smoothies".
**Index** — a lookup accelerator the database maintains. We indexed what we search by:
`phone` (tracker) · `status` (admin lists) · `payment_status + placed_at` (the 60-minute
auto-cancel job).
**Model / Eloquent** — a PHP class standing for one table row; you find and save data through
it (`MenuItem::where('slug','abuja-tonic')->first()`). Eloquent = Laravel's name for this.
**Relation** — how models connect: Category `hasMany` items · MenuItem `belongsTo` category.
**Seeder** — a PHP file that fills tables with real starting data (our 64 menu items, 10
settings). `php artisan migrate:fresh --seed` = tear down, rebuild, and fill everything.
**Snapshot** — data copied and frozen at a moment: order lines copy name + price from the
menu, so an old order always shows exactly what the customer paid.
**Tinker** — Laravel's interactive PHP playground in the terminal — perfect for peeking at data.

## Coming up (explained when we reach them)

**Route / Controller / Blade / SASS / Middleware** — *(Days 3–5, each gets its entry the day
we meet it)*

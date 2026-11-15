# 🍊 CitrusSplash & Restaurant — Ordering Web App

The real ordering website for **CitrusSplash & Restaurant** — Suite A105, Stick and Stone Plaza,
Ademola Adetokunbo Crescent, Wuse, Abuja. Fresh smoothies, salads and toasted sandwiches,
ordered online in under a minute: no login, no friction, no waiting to find out.

## What this is

A **Laravel + Blade + Bootstrap 5 (themed via SASS) + vanilla JavaScript** web application.
Customers browse the menu, fill a basket, check out as guests, pay by **bank transfer**
(order number as the narration), and track their order live. Staff manage orders, the menu
and settings from a 2FA-protected admin panel at `/admin`.

> **Status:** under active construction (10-day build). This README grows with the app.

## Repository layout

| Path | What it is |
|---|---|
| `docs/` | The 8 governing plan documents + the system diagram — read these first |
| `LEARNING.md` | The owner's growing glossary — every new concept, explained plainly |
| `design-reference/` | *Local only, never committed* — the approved HTML prototype used as the visual blueprint |
| *(Laravel app folders land here from Day 2)* | `app/`, `routes/`, `resources/views`, … |

## Running it (once Laravel lands — Day 2)

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan serve        # → http://127.0.0.1:8000
```

## The people

- **Victor Lawrence** — owner & developer (learning full-stack by building the real thing)
- **The AI agent** — guide-builder: writes code, explains every step, follows `docs/Agent.md`

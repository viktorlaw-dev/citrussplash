<?php

use App\Http\Controllers\MenuDataController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// ---- Customer pages (Phase 4). Admin routes arrive in Phase 7, behind the staff gate. ----

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');

// Placeholder pages — links exist today, real builds land later in Phase 4.
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');
Route::get('/status', [PageController::class, 'status'])->name('status');
Route::get('/about', [PageController::class, 'about'])->name('about');

// The menu as JavaScript, live from the database — same shape as the
// prototype's static file. (Listed as Phase 5 in the docs; pulled forward
// one phase because the menu page needs it to render at all.)
Route::get('/js/menu-data.js', MenuDataController::class)->name('menu-data');

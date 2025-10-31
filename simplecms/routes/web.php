<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MediaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// Homepage - either custom page or blog listing
Route::get('/', [HomeController::class, 'index'])->name('home');

// Search route
Route::get('/search', [FrontendPostController::class, 'search'])->name('search');

// Blog routes
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [FrontendPostController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [FrontendPostController::class, 'category'])->name('category');
    Route::get('/{slug}', [FrontendPostController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // User dashboard (for non-admin users)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| All admin routes are protected by auth and admin middleware
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // Admin Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        // Admin Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Admin Logout
        Route::post('/logout', function() {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/');
        })->name('logout');

        // Page Management (Resource Controller)
        Route::resource('pages', PageController::class);

        // Page Sections Management (nested under pages)
        Route::post('pages/{page}/sections', [PageController::class, 'storeSection'])->name('pages.sections.store');
        Route::get('pages/{page}/sections/{section}', [PageController::class, 'updateSection'])->name('pages.sections.get');
        Route::put('pages/{page}/sections/{section}', [PageController::class, 'updateSection'])->name('pages.sections.update');
        Route::delete('pages/{page}/sections/{section}', [PageController::class, 'destroySection'])->name('pages.sections.destroy');
        Route::post('pages/{page}/sections/reorder', [PageController::class, 'reorderSections'])->name('pages.sections.reorder');

        // Post Management (Resource Controller)
        Route::resource('posts', PostController::class);

        // Category Management (Resource Controller)
        Route::resource('categories', CategoryController::class);

        // Menu Management (Resource Controller)
        Route::resource('menus', MenuController::class);

        // Menu Items Management (nested under menus)
        Route::get('menus/{menu}/items/{item}', [MenuController::class, 'getItem'])->name('menus.items.get');
        Route::post('menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
        Route::put('menus/{menu}/items/{item}', [MenuController::class, 'updateItem'])->name('menus.items.update');
        Route::delete('menus/{menu}/items/{item}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');
        Route::post('menus/{menu}/items/reorder', [MenuController::class, 'reorderItems'])->name('menus.items.reorder');

        // Theme Management
        Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
        Route::post('themes', [ThemeController::class, 'store'])->name('themes.store');
        Route::get('themes/{id}', [ThemeController::class, 'show'])->name('themes.show');
        Route::post('themes/{id}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
        Route::delete('themes/{id}', [ThemeController::class, 'destroy'])->name('themes.destroy');

        // Theme Settings Management
        Route::get('themes/{theme}/settings', [\App\Http\Controllers\Admin\ThemeSettingController::class, 'edit'])->name('themes.settings.edit');
        Route::post('themes/{theme}/settings', [\App\Http\Controllers\Admin\ThemeSettingController::class, 'update'])->name('themes.settings.update');
        Route::post('themes/{theme}/settings/create', [\App\Http\Controllers\Admin\ThemeSettingController::class, 'store'])->name('themes.settings.store');
        Route::delete('themes/{theme}/settings/{setting}', [\App\Http\Controllers\Admin\ThemeSettingController::class, 'destroy'])->name('themes.settings.destroy');

        // Settings Management
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::match(['post', 'put', 'patch'], 'settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('settings/{group}', [SettingController::class, 'showGroup'])->name('settings.group');

        // Media Library Management
        Route::get('media', [MediaController::class, 'index'])->name('media.index');
        Route::get('media/create', [MediaController::class, 'create'])->name('media.create');
        Route::post('media', [MediaController::class, 'store'])->name('media.store');
        Route::post('media/upload', [MediaController::class, 'upload'])->name('media.upload');
        Route::get('media/{id}', [MediaController::class, 'show'])->name('media.show');
        Route::get('media/{id}/edit', [MediaController::class, 'edit'])->name('media.edit');
        Route::put('media/{id}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');
    });

/*
|--------------------------------------------------------------------------
| Dynamic Page Routes (MUST BE LAST - Catch-all)
|--------------------------------------------------------------------------
*/

// Dynamic page routes (catch-all for custom pages)
// IMPORTANT: This MUST be the last route defined to avoid catching other routes
Route::get('/{slug}', [FrontendPageController::class, 'show'])->name('page.show');

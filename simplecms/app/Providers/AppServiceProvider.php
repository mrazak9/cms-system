<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings, menus, and active theme with all frontend views
        View::composer(['frontend.*', 'home*'], function ($view) {
            // Get all settings as key-value array
            $settings = Setting::pluck('value', 'key')->toArray();

            // Get active theme
            $activeTheme = \App\Models\Theme::where('is_active', true)->first();

            // Get theme settings for active theme
            $themeSettings = [];
            if ($activeTheme) {
                $themeSettings = \App\Models\ThemeSetting::getForTheme($activeTheme->id);
            }

            // Get primary menu with items
            $primaryMenu = Menu::with(['menuItems' => function ($query) {
                $query->whereNull('parent_id')
                    ->with('children')
                    ->orderBy('order');
            }])
            ->where('location', 'primary')
            ->where('is_active', true)
            ->first();

            // Get footer menu with items
            $footerMenu = Menu::with(['menuItems' => function ($query) {
                $query->whereNull('parent_id')
                    ->with('children')
                    ->orderBy('order');
            }])
            ->where('location', 'footer')
            ->where('is_active', true)
            ->first();

            $view->with([
                'settings' => $settings,
                'activeTheme' => $activeTheme,
                'themeSettings' => $themeSettings,
                'primaryMenu' => $primaryMenu,
                'footerMenu' => $footerMenu,
            ]);
        });
    }
}

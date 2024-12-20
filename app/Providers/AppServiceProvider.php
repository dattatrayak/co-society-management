<?php

namespace App\Providers;

use App\Models\SocietyMenu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
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
        View::composer('*', function ($view) {
            $menus = Menu::whereNull('parent_id')->with('children.children')->orderBy('order')->get();
            $view->with('menus', $menus);
            $societyMenu = SocietyMenu::whereNull('parent_id')->with('children.children')->orderBy('order')->get();
            $view->with('societyMenu', $societyMenu);
        });
    }
}

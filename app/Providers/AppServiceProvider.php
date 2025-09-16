<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
      Paginator::useBootstrapFour();

        View::composer('*', function ($view) {
            $authUser= Auth::user();
            $userRole = $authUser ? ucfirst($authUser->getRoleNames()->first() ?? 'N/A') : 'N/A';
            $view->with('authUser', $authUser);
            $view->with('userRole', $userRole);
        });
    }
}

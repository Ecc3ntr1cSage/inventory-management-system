<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('admin', function ($user) {
            return $user->role == 'admin';
        });

        Gate::define('staff', function ($user) {
            return $user->role == 'staff';
        });

        Gate::define('both', function ($user) {
            return $user->role == 'admin' || $user->role == 'staff';
        });

        Gate::define('user', function ($user) {
            return $user->role == 'user';
        });

    }
}

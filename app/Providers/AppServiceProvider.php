<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void {}


    public function boot(): void
    {
        Gate::define('employee.view', function ($user) {
            return $user->hasPermission('employee.view');
        });

        Gate::define('employee.create', function ($user) {
            return $user->hasPermission('employee.create');
        });

        Gate::define('employee.update', function ($user) {
            return $user->hasPermission('employee.update');
        });

        Gate::define('employee.delete', function ($user) {
            return $user->hasPermission('employee.delete');
        });
    }
}

<?php

namespace FoxRunHoldings\LaravelTeams\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;

class UserModelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // This approach would require using macros or other techniques
        // to extend the User model dynamically
    }

    public function register()
    {
        // Alternative approach: Use a custom User model
        $this->app->bind('auth.providers.users.model', function ($app) {
            return User::class;
        });
    }
} 
<?php

namespace FoxRunHoldings\LaravelTeams;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireTeamsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Livewire components
        Livewire::component('laravel-teams::settings.team', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Team::class);
        Livewire::component('laravel-teams::settings.team-switcher', \FoxRunHoldings\LaravelTeams\Livewire\Settings\TeamSwitcher::class);

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'laravel-teams-migrations');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-teams'),
        ], 'laravel-teams-views');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-teams');
    }
} 
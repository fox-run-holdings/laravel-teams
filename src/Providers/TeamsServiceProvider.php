<?php

namespace FoxRunHoldings\LaravelTeams\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Facade;
use Livewire\Livewire;
use FoxRunHoldings\LaravelTeams\Livewire\Teams\Invitations;
use FoxRunHoldings\LaravelTeams\Livewire\Teams\Manage;
use FoxRunHoldings\LaravelTeams\Livewire\Teams\Members;
use FoxRunHoldings\LaravelTeams\Livewire\Teams\Switcher;
use FoxRunHoldings\LaravelTeams\Teams;
use FoxRunHoldings\LaravelTeams\Facades\Teams as TeamsFacade;

class TeamsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/teams.php', 'teams'
        );

        $this->app->singleton('laravel-teams', function ($app) {
            return new Teams();
        });

        $this->app->alias('laravel-teams', Teams::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'laravel-teams');
        
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        
        $this->registerLivewireComponents();
        
        $this->publishes([
            __DIR__.'/../Config/teams.php' => config_path('teams.php'),
        ], 'laravel-teams-config');
        
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/laravel-teams'),
        ], 'laravel-teams-views');
    }
    
    /**
     * Register Livewire components.
     */
    protected function registerLivewireComponents(): void
    {
        Livewire::component('teams.invitations', Invitations::class);
        Livewire::component('teams.manage', Manage::class);
        Livewire::component('teams.members', Members::class);
        Livewire::component('teams.switcher', Switcher::class);
    }
} 
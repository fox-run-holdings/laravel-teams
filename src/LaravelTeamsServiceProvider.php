<?php
    
    namespace FoxRunHoldings\LaravelTeams;
    
    use FoxRunHoldings\LaravelTeams\Extensions\UserModelExtension;
    use Illuminate\Support\Facades\Gate;
    use Illuminate\Support\ServiceProvider;
    use Livewire\Livewire;
    
    class LaravelTeamsServiceProvider extends ServiceProvider {
        public function boot() {
            $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
            $this->loadViewsFrom(__DIR__ . '/resources/views', 'laravel-teams');
            $this->loadRoutesFrom(__DIR__ . '/routes/teams.php');
            
            // Register middleware
            $this->app['router']->aliasMiddleware('ensure.user.has.team', \FoxRunHoldings\LaravelTeams\Middleware\EnsureUserHasTeam::class);
            
            // Automatically extend the User model
            $this->extendUserModel();
            
            // Register Livewire components
            Livewire::component('teams.teams', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\Teams::class);
            Livewire::component('teams.manage-team-settings', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\ManageTeamSettings::class);
            Livewire::component('teams.manage-team-members', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\ManageTeamMembers::class);
            Livewire::component('teams.team-invitations', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\TeamInvitations::class);
            Livewire::component('team-dropdown', \FoxRunHoldings\LaravelTeams\Livewire\TeamDropdown::class);
            
            // Register policies
            Gate::policy(\FoxRunHoldings\LaravelTeams\Models\Team::class, \FoxRunHoldings\LaravelTeams\Policies\TeamPolicy::class);
            
            $this->publishes([
                __DIR__ . '/resources/views' => resource_path('views/vendor/laravel-teams'),
            ], 'laravel-teams-views');
            
            $this->publishes([
                __DIR__ . '/config/teams.php' => config_path('teams.php'),
            ], 'laravel-teams-config');
            
            $this->publishes([
                __DIR__ . '/resources/views/teams.blade.php' => resource_path('views/teams.blade.php'),
            ], 'laravel-teams-views');
        }
        
        public function register() {
            $this->mergeConfigFrom(__DIR__ . '/config/teams.php', 'teams');
            
            // Register commands
            if ($this->app->runningInConsole()) {
                $this->commands([
                    \FoxRunHoldings\LaravelTeams\Console\InstallCommand::class,
                ]);
            }
        }
        
        protected function extendUserModel()
        {
            // Get the User model class from config
            $userClass = config('auth.providers.users.model');
            
            if (class_exists($userClass)) {
                UserModelExtension::extend($userClass);
                
                // Also add the fillable field for current_team_id
                $userClass::macro('getFillable', function () {
                    $fillable = $this->fillable ?? [];
                    if (!in_array('current_team_id', $fillable)) {
                        $fillable[] = 'current_team_id';
                    }
                    return $fillable;
                });
            }
        }
    }
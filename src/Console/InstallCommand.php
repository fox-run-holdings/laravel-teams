<?php

namespace FoxRunHoldings\LaravelTeams\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'teams:install {--force : Overwrite existing files} {--update : Update existing installation}';
    
    protected $description = 'Install or update the Laravel Teams package';

    public function handle()
    {
        if ($this->option('update')) {
            $this->update();
            return;
        }

        $this->info('Installing Laravel Teams...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--tag' => 'laravel-teams-config',
            '--force' => $this->option('force')
        ]);

        // Publish views
        $this->call('vendor:publish', [
            '--tag' => 'laravel-teams-views',
            '--force' => $this->option('force')
        ]);

        // Run migrations
        $this->call('migrate');

        // Add teams route to web.php if not exists
        $this->addTeamsRoute();

        // Add teams navigation if not exists
        $this->addTeamsNavigation();

        $this->info('Laravel Teams installed successfully!');
        $this->info('');
        $this->info('Next steps:');
        $this->info('1. Add <livewire:team-dropdown /> to your navbar');
        $this->info('2. Visit /team to start managing teams');
        $this->info('3. Check the documentation at: https://github.com/fox-run-holdings/laravel-teams');
    }

    protected function update()
    {
        $this->info('Updating Laravel Teams...');

        // Publish updated configuration
        $this->call('vendor:publish', [
            '--tag' => 'laravel-teams-config',
            '--force' => true
        ]);

        // Publish updated views
        $this->call('vendor:publish', [
            '--tag' => 'laravel-teams-views',
            '--force' => true
        ]);

        // Run migrations
        $this->call('migrate');

        // Clear caches
        $this->call('view:clear');
        $this->call('config:clear');
        $this->call('route:clear');

        $this->info('Laravel Teams updated successfully!');
        $this->info('');
        $this->info('Your teams functionality has been updated to the latest version.');
    }

    protected function addTeamsRoute()
    {
        $webRoutesPath = base_path('routes/web.php');
        $teamsRoutes = "// Teams routes\nRoute::middleware(['auth'])->group(function () {\n    Route::get('team/{team_id?}', \\FoxRunHoldings\\LaravelTeams\\Livewire\\Settings\\Teams\\Teams::class)->name('team');\n    Route::get('team/{team_id}/manage', \\FoxRunHoldings\\LaravelTeams\\Livewire\\Settings\\Teams\\ManageTeamSettings::class)->name('team.manage');\n});";

        if (File::exists($webRoutesPath)) {
            $content = File::get($webRoutesPath);
            
            if (!str_contains($content, 'team')) {
                $content = str_replace(
                    'require __DIR__.\'/auth.php\';',
                    "require __DIR__.'/auth.php';\n\n$teamsRoutes",
                    $content
                );
                
                File::put($webRoutesPath, $content);
                $this->info('✓ Added teams routes to web.php');
            } else {
                $this->info('✓ Teams routes already exist in web.php');
            }
        }
    }

    protected function addTeamsNavigation()
    {
        // Try multiple possible header locations for Laravel 12 + Flux
        $possibleHeaderPaths = [
            resource_path('views/components/layouts/app/header.blade.php'),
            resource_path('views/components/layouts/header.blade.php'),
            resource_path('views/layouts/header.blade.php'),
            resource_path('views/components/header.blade.php'),
        ];
        
        $headerPath = null;
        foreach ($possibleHeaderPaths as $path) {
            if (File::exists($path)) {
                $headerPath = $path;
                break;
            }
        }
        
        if ($headerPath) {
            $content = File::get($headerPath);
            
            if (!str_contains($content, 'team-dropdown')) {
                // Add team dropdown component - try to find a good insertion point
                if (str_contains($content, 'Dashboard')) {
                    $content = str_replace(
                        '{{ __(\'Dashboard\') }}',
                        "{{ __('Dashboard') }}\n                <livewire:team-dropdown />",
                        $content
                    );
                } elseif (str_contains($content, 'flux:navbar.item')) {
                    // Add after existing navbar items
                    $content = str_replace(
                        '</flux:navbar>',
                        "                <livewire:team-dropdown />\n            </flux:navbar>",
                        $content
                    );
                }
                
                File::put($headerPath, $content);
                $this->info('✓ Added team dropdown to header');
            } else {
                $this->info('✓ Team dropdown already exists in header');
            }
        } else {
            $this->warn('⚠ Could not find header file to add navigation. Please add <livewire:team-dropdown /> to your navbar manually.');
        }
    }
} 
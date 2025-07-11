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
        $this->info('1. Add <livewire:teams.teams /> to your settings page');
        $this->info('2. Visit /teams to start managing teams');
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
        $teamsRoute = "Route::get('teams', function () {\n    return view('teams');\n})->name('teams');";

        if (File::exists($webRoutesPath)) {
            $content = File::get($webRoutesPath);
            
            if (!str_contains($content, 'teams')) {
                $content = str_replace(
                    'require __DIR__.\'/auth.php\';',
                    "require __DIR__.'/auth.php';\n\n// Teams routes\nRoute::middleware(['auth'])->group(function () {\n    $teamsRoute\n});",
                    $content
                );
                
                File::put($webRoutesPath, $content);
                $this->info('✓ Added teams route to web.php');
            } else {
                $this->info('✓ Teams route already exists in web.php');
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
            
            if (!str_contains($content, 'teams')) {
                // Add teams navigation item - try to find a good insertion point
                if (str_contains($content, 'Dashboard')) {
                    $content = str_replace(
                        '{{ __(\'Dashboard\') }}',
                        "{{ __('Dashboard') }}\n                <flux:navbar.item icon=\"users\" :href=\"route('teams')\" :current=\"request()->routeIs('teams')\" wire:navigate>\n                    {{ __('Teams') }}\n                </flux:navbar.item>",
                        $content
                    );
                } elseif (str_contains($content, 'flux:navbar.item')) {
                    // Add after existing navbar items
                    $content = str_replace(
                        '</flux:navbar>',
                        "                <flux:navbar.item icon=\"users\" :href=\"route('teams')\" :current=\"request()->routeIs('teams')\" wire:navigate>\n                    {{ __('Teams') }}\n                </flux:navbar.item>\n            </flux:navbar>",
                        $content
                    );
                }
                
                File::put($headerPath, $content);
                $this->info('✓ Added teams navigation to header');
            } else {
                $this->info('✓ Teams navigation already exists in header');
            }
        } else {
            $this->warn('⚠ Could not find header file to add navigation. Please add teams navigation manually.');
        }
    }
} 
<?php

namespace FoxRunHoldings\LaravelTeams\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'teams:install {--force : Overwrite existing files}';
    
    protected $description = 'Install the Laravel Teams package';

    public function handle()
    {
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
        $this->info('3. Check the documentation at: https://github.com/your-repo/laravel-teams');
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
        $headerPath = resource_path('views/components/layouts/app/header.blade.php');
        
        if (File::exists($headerPath)) {
            $content = File::get($headerPath);
            
            if (!str_contains($content, 'teams')) {
                // Add teams navigation item
                $content = str_replace(
                    '{{ __(\'Dashboard\') }}',
                    "{{ __('Dashboard') }}\n                <flux:navbar.item icon=\"users\" :href=\"route('teams')\" :current=\"request()->routeIs('teams')\" wire:navigate>\n                    {{ __('Teams') }}\n                </flux:navbar.item>",
                    $content
                );
                
                File::put($headerPath, $content);
                $this->info('✓ Added teams navigation to header');
            } else {
                $this->info('✓ Teams navigation already exists in header');
            }
        }
    }
} 
<?php

/**
 * Laravel Teams Package Installation Script
 * 
 * This script helps set up the Laravel Teams package in your Laravel application.
 * Run this script from your Laravel project root.
 */

echo "Laravel Teams Package Installation\n";
echo "================================\n\n";

// Check if we're in a Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: This doesn't appear to be a Laravel project.\n";
    echo "Please run this script from your Laravel project root.\n";
    exit(1);
}

// Check if composer.json exists
if (!file_exists('composer.json')) {
    echo "❌ Error: composer.json not found.\n";
    exit(1);
}

echo "✅ Laravel project detected.\n\n";

// Check if the package is already installed
if (file_exists('vendor/fox-run-holdings/laravel-teams')) {
    echo "✅ Laravel Teams package is already installed.\n\n";
} else {
    echo "❌ Laravel Teams package is not installed.\n";
    echo "Please install it first with: composer require fox-run-holdings/laravel-teams\n\n";
    exit(1);
}

// Publish configuration
echo "📦 Publishing configuration...\n";
exec('php artisan vendor:publish --provider="FoxRunHoldings\LaravelTeams\Providers\TeamsServiceProvider"', $output, $returnCode);

if ($returnCode === 0) {
    echo "✅ Configuration published successfully.\n\n";
} else {
    echo "❌ Failed to publish configuration.\n";
    echo "Output: " . implode("\n", $output) . "\n\n";
}

// Run migrations
echo "🗄️  Running migrations...\n";
exec('php artisan migrate', $output, $returnCode);

if ($returnCode === 0) {
    echo "✅ Migrations completed successfully.\n\n";
} else {
    echo "❌ Failed to run migrations.\n";
    echo "Output: " . implode("\n", $output) . "\n\n";
}

echo "🎉 Installation completed!\n\n";
echo "Next steps:\n";
echo "1. Add team relationships to your User model (see README.md)\n";
echo "2. Add routes to your routes/web.php file\n";
echo "3. Include Livewire components in your views\n";
echo "4. Configure the package in config/teams.php\n\n";
echo "For detailed instructions, see the README.md file.\n"; 
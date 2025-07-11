# Laravel Teams Installation Guide

This guide will help you install and configure the Laravel Teams package in your Laravel application.

## Prerequisites

- Laravel 12.x
- PHP 8.4+
- Livewire 3.x
- Flux UI components (included with Laravel 12 starter kit)

## Step 1: Install the Package

```bash
composer require fox-run-holdings/laravel-teams
```

## Step 2: Run the Installation Command

```bash
php artisan teams:install
```

This command will automatically:
- Publish configuration and views
- Run migrations
- Add teams route to `routes/web.php`
- Add teams navigation to your header
- Create the teams view

### Alternative: Manual Installation

If you prefer to install manually:

```bash
# Publish configuration and views
php artisan vendor:publish --tag=laravel-teams-config
php artisan vendor:publish --tag=laravel-teams-views

# Run migrations
php artisan migrate
```

## Step 4: Add Teams to Your Settings

Add the teams component to your settings page. In your settings layout or view:

```blade
<livewire:teams.teams />
```

## Step 5: Add Teams Navigation (Optional)

Add a teams link to your navigation:

```blade
<flux:navbar.item icon="users" :href="route('teams')" :current="request()->routeIs('teams')" wire:navigate>
    {{ __('Teams') }}
</flux:navbar.item>
```

## Step 6: Configure User Model (Already Done)

The package automatically extends your User model with team functionality. The following relationships and methods are added:

- `$user->teams` - Teams the user belongs to
- `$user->ownedTeams` - Teams the user owns
- `$user->currentTeam` - User's current team
- `$user->personalTeam` - User's personal team
- `$user->switchTeam($team)` - Switch to a different team

## Step 7: Test the Installation

1. Create a new user account
2. Navigate to the teams page
3. Create a new team
4. Invite other users to your team

## Configuration

The package configuration is located in `config/teams.php`. You can customize:

- Team roles and permissions
- Personal team creation
- Invitation system settings

## Usage Examples

### Creating a Team

```php
use FoxRunHoldings\LaravelTeams\Models\Team;

$team = Team::create([
    'name' => 'My Team',
    'owner_id' => auth()->id(),
]);
```

### Adding Users to Teams

```php
$team->users()->attach($userId, ['role' => 'member']);
```

### Checking Permissions

```php
if ($team->userHasPermission($user, 'write')) {
    // User can write to team
}
```

### Switching Teams

```php
$user->switchTeam($team);
```

## Troubleshooting

### Common Issues

1. **Migration errors**: Make sure you're using Laravel 12 and have run `php artisan migrate`
2. **Component not found**: Ensure Livewire is properly installed and the service provider is registered
3. **Permission errors**: Check that the user has the correct team permissions

### Support

If you encounter any issues, please check the main README.md file or create an issue on the package repository. 
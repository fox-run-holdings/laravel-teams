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

## Step 3: Add Teams to Your Settings

Add the teams component to your settings page. In your settings layout or view:

```blade
<livewire:teams.teams />
```

## Step 4: Add Teams Navigation (Optional)

Add a teams link to your navigation:

```blade
<flux:navbar.item icon="users" :href="route('team')" :current="request()->routeIs('team*')" wire:navigate>
    {{ __('Teams') }}
</flux:navbar.item>
```

## Step 5: Configure User Model (Already Done)

The package automatically extends your User model with team functionality. The following relationships and methods are added:

- `$user->teams` - Teams the user belongs to
- `$user->ownedTeams` - Teams the user owns
- `$user->currentTeam` - User's current team
- `$user->personalTeam` - User's personal team
- `$user->switchTeam($team)` - Switch to a different team

## Step 6: Test the Installation

1. Create a new user account
2. Navigate to the teams page
3. Create a new team
4. Invite other users to your team

## Updating the Package

### Automatic Update

To update to the latest version:

1. **Update the package**:
   ```bash
   composer update fox-run-holdings/laravel-teams
   ```

2. **Run the update command**:
   ```bash
   php artisan teams:install --update
   ```

This will automatically:
- Publish updated configuration and views
- Run any new migrations
- Clear all caches (views, config, routes)

### Manual Update

If you prefer to update manually:

```bash
# Publish updated assets
php artisan vendor:publish --tag=laravel-teams-config --force
php artisan vendor:publish --tag=laravel-teams-views --force

# Run migrations
php artisan migrate

# Clear caches
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

## Routing Structure

The package provides a simplified routing structure:

### Team Management Routes

- **`/team`** - Teams listing and creation (when no team is selected)
- **`/team/{team_id}`** - Specific team management (when a team is selected)

### URL Examples

- `/team` - View all teams, create new team, or select existing team
- `/team/123` - Manage team with ID 123 (settings, members, invitations)

## Configuration

The package configuration is located in `config/teams.php`. You can customize:

### Basic Configuration
```php
return [
    'roles' => [
        'owner' => [
            'permissions' => ['*'], // All permissions
        ],
        'admin' => [
            'permissions' => ['read', 'write', 'delete', 'invite'],
        ],
        'member' => [
            'permissions' => ['read', 'write'],
        ],
        'viewer' => [
            'permissions' => ['read'],
        ],
    ],
];
```

## Team Management

### Creating Teams

Teams can be created through the web interface or programmatically:

```php
use FoxRunHoldings\LaravelTeams\Models\Team;

$team = Team::create([
    'name' => 'My Team',
    'owner_id' => auth()->id(),
    'personal_team' => false,
]);
```

### Managing Team Members

```php
// Add a user to a team
$team->users()->attach($userId, ['role' => 'member']);

// Remove a user from a team
$team->users()->detach($userId);

// Update user role
$team->users()->updateExistingPivot($userId, ['role' => 'admin']);
```

### Checking Permissions

```php
// Check if user has specific permission
if ($team->userHasPermission($user, 'write')) {
    // User can write to team
}

// Check if user is team owner
if ($team->isOwnedBy($user)) {
    // User owns the team
}

// Check if user is team member
if ($team->hasUser($user)) {
    // User is a member of the team
}
```

## Troubleshooting

### Common Issues

1. **"Class not found" errors**: Clear your composer autoload cache:
   ```bash
   composer dump-autoload
   ```

2. **Views not updating**: Clear view cache:
   ```bash
   php artisan view:clear
   ```

3. **Routes not working**: Clear route cache:
   ```bash
   php artisan route:clear
   ```

4. **Configuration not loading**: Clear config cache:
   ```bash
   php artisan config:clear
   ```

### Getting Help

If you encounter issues:

1. Check the [GitHub Issues](https://github.com/fox-run-holdings/laravel-teams/issues)
2. Review the [Documentation](https://github.com/fox-run-holdings/laravel-teams/wiki)
3. Join the [Discussions](https://github.com/fox-run-holdings/laravel-teams/discussions)

## Next Steps

After installation, you can:

1. **Customize the views** by publishing them to your application
2. **Extend the models** to add custom functionality
3. **Add custom roles and permissions** through configuration
4. **Integrate with your existing authentication** system
5. **Add team-aware middleware** to your routes

For more advanced usage, see the [API Reference](https://github.com/fox-run-holdings/laravel-teams/wiki/API-Reference) in the documentation. 
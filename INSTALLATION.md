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
- Add teams routes to `routes/web.php`
- Add team dropdown to your navbar
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

## Step 3: Add Team Dropdown to Navbar

The installation command automatically adds the team dropdown to your navbar. If you need to add it manually:

```blade
<livewire:team-dropdown />
```

## Step 4: Configure User Model (Already Done)

The package automatically extends your User model with team functionality. The following relationships and methods are added:

- `$user->teams` - Teams the user belongs to
- `$user->ownedTeams` - Teams the user owns
- `$user->currentTeam` - User's current team
- `$user->personalTeam` - User's personal team
- `$user->switchTeam($team)` - Switch to a different team

## Step 5: Test the Installation

1. Create a new user account
2. Check that the team dropdown appears in your navbar
3. Click "Create New Team" to create your first team
4. Create a new team and verify it appears in the dropdown
5. Click "Manage Team" to manage the current team

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

The package provides a simplified routing structure that matches Laravel Jetstream's team management:

### Team Management Routes

- **`/team`** - Team creation and listing (when no team is selected)
- **`/team/{team_id}/manage`** - Team management (settings, members, invitations)

### URL Examples

- `/team` - View all teams, create new team, or select existing team
- `/team/123/manage` - Manage team with ID 123 (settings, members, invitations)

## User Interface

### Navbar Team Dropdown

The team dropdown in the navbar provides:

- **Current team display**: Shows the current team name or "No team"
- **Team switching**: Click "Switch to" for other teams
- **Team management**: Click "Manage Team" for current team
- **Create new team**: "Create New Team" option at the bottom
- **Visual indicators**: Current team is highlighted with a checkmark

### Team Management Pages

#### Team Creation (`/team`)
When no team is selected, users see:
- List of all teams they belong to
- Form to create a new team
- Option to switch between teams
- Delete teams (if they own them)

#### Team Management (`/team/{team_id}/manage`)
When a specific team is selected, users see:
- Team settings (name, slug)
- Member management
- Team invitations
- Role management

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

### Personal Teams

The package automatically creates a personal team for each user. Personal teams:
- Are created automatically when a user first accesses team features
- Cannot be deleted
- Are owned by the user
- Have the `personal_team` flag set to `true`

### Team Switching

Users can switch between teams using:

```php
// Programmatically
auth()->user()->switchTeam($team);

// Via the dropdown
// The dropdown automatically handles team switching
```

### Team Permissions

The package includes a comprehensive permission system:

```php
// Check if user has permission
if ($team->userHasPermission(auth()->id(), 'write')) {
    // User can write to team
}

// Check if user is owner
if ($team->isOwnedBy(auth()->id())) {
    // User owns the team
}

// Check if user is member
if ($team->hasUser(auth()->id())) {
    // User is a member of the team
}
```

## Middleware

The package includes middleware to ensure users have teams:

```php
Route::middleware(['auth', 'ensure.user.has.team'])->group(function () {
    // Routes that require a team
});
```

This middleware:
- Automatically creates a personal team for users who don't have any teams
- Sets the current team if none is selected
- Ensures users always have a team context

## Customization

### Custom Team Roles

You can customize team roles in `config/teams.php`:

```php
'roles' => [
    'owner' => [
        'permissions' => ['*'],
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
```

### Custom Views

Publish the views to customize the UI:

```bash
php artisan vendor:publish --tag=laravel-teams-views
```

The views will be published to `resources/views/vendor/laravel-teams/`.

### Custom Components

Extend the Livewire components to add custom functionality:

```php
class CustomTeamComponent extends \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\Teams
{
    // Add your custom logic
}
```

## Troubleshooting

### Common Issues

1. **Team dropdown not appearing**: Make sure the component is added to your navbar
2. **Routes not working**: Check that the routes are properly added to `routes/web.php`
3. **Permissions errors**: Ensure the user has the correct role in the team
4. **Personal team not created**: The middleware should handle this automatically

### Debugging

Enable debug mode to see detailed error messages:

```php
// In config/app.php
'debug' => true,
```

### Support

If you encounter issues:
1. Check the documentation
2. Search existing issues on GitHub
3. Create a new issue with detailed information

## Next Steps

After installation, you can:

1. **Customize the UI** by publishing and modifying views
2. **Add team-aware routes** using the middleware
3. **Extend functionality** by creating custom components
4. **Configure roles and permissions** to match your needs

The package is designed to be simple yet powerful, providing all the team management features you need for a Laravel 12 application with Livewire 3 and Flux UI. 
# Laravel Teams

A comprehensive team management package for Laravel 12 applications, providing Jetstream-level team functionality with modern Livewire 3 and Flux UI integration.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fox-run-holdings/laravel-teams.svg)](https://packagist.org/packages/fox-run-holdings/laravel-teams)
[![Total Downloads](https://img.shields.io/packagist/dt/fox-run-holdings/laravel-teams.svg)](https://packagist.org/packages/fox-run-holdings/laravel-teams)
[![License](https://img.shields.io/github/license/fox-run-holdings/laravel-teams.svg)](https://github.com/fox-run-holdings/laravel-teams/blob/main/LICENSE.md)

## 🚀 Features

### Core Team Management
- ✅ **Create, edit, and delete teams** with full CRUD operations
- ✅ **Seamless team switching** via navbar dropdown
- ✅ **Personal teams** automatically created for new users
- ✅ **Soft deletes** for team restoration if needed
- ✅ **Team slugs** for SEO-friendly URLs

### Role-Based Access Control
- ✅ **Four predefined roles**: Owner, Admin, Member, and Viewer
- ✅ **Granular permissions**: Read, write, delete, and invite
- ✅ **Policy-based authorization** for comprehensive security
- ✅ **Customizable roles and permissions** via configuration

### Team Collaboration
- ✅ **Email-based team invitations** with role assignment
- ✅ **Invitation management** with accept/decline functionality
- ✅ **Member management** with role changes and removal
- ✅ **Team settings** for configuration and customization

### Modern UI/UX
- ✅ **Flux UI integration** using Laravel 12's Flux components
- ✅ **Livewire 3 components** for real-time interactions
- ✅ **Navbar dropdown** for easy team switching
- ✅ **Responsive design** that works on all devices
- ✅ **Dark mode support** with automatic theme switching
- ✅ **Accessibility features** for inclusive design

### Developer Experience
- ✅ **One-command installation** with automatic setup
- ✅ **One-command updates** for seamless upgrades
- ✅ **Comprehensive API** with helper methods and traits
- ✅ **Middleware integration** for team-aware routes
- ✅ **Extensible architecture** for custom modifications
- ✅ **Full documentation** and examples

## 📋 Requirements

- **PHP**: ^8.4
- **Laravel**: ^12.0
- **Livewire**: ^3.0
- **Flux UI**: Laravel 12's Flux components

## 🛠️ Installation

### Quick Install (Recommended)

1. **Install the package**:
   ```bash
   composer require fox-run-holdings/laravel-teams
   ```

2. **Run the installation command**:
   ```bash
   php artisan teams:install
   ```

This will automatically:
- ✅ Publish configuration and views
- ✅ Run database migrations
- ✅ Add teams routes to `routes/web.php`
- ✅ Add team dropdown to your navbar
- ✅ Create the teams view

### Manual Installation

If you prefer to install manually:

1. **Install the package**:
   ```bash
   composer require fox-run-holdings/laravel-teams
   ```

2. **Publish the configuration and views**:
   ```bash
   php artisan vendor:publish --tag=laravel-teams-config
   php artisan vendor:publish --tag=laravel-teams-views
   ```

3. **Run the migrations**:
   ```bash
   php artisan migrate
   ```

4. **Add the teams routes to `routes/web.php`**:
   ```php
   Route::middleware(['auth', 'ensure.user.has.team'])->group(function () {
       Route::get('team/{team_id?}', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\Teams::class)->name('team');
       Route::get('team/{team_id}/manage', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\ManageTeamSettings::class)->name('team.manage');
   });
   ```

5. **Add team dropdown to your navbar** (optional):
   ```blade
   <livewire:team-dropdown />
   ```

## 🔄 Updating

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
- ✅ Publish updated configuration and views
- ✅ Run any new migrations
- ✅ Clear all caches (views, config, routes)

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

## 🛣️ Routing

The package provides a simplified routing structure that matches Laravel Jetstream's team management:

### Team Management Routes

- **`/team`** - Team creation and listing (when no team is selected)
- **`/team/{team_id}/manage`** - Team management (settings, members, invitations)

### URL Examples

- `/team` - View all teams, create new team, or select existing team
- `/team/123/manage` - Manage team with ID 123 (settings, members, invitations)

## 🎯 User Interface

### Navbar Team Dropdown

The package automatically adds a team dropdown to your navbar that:

- **Shows current team** or "No team" if none selected
- **Lists all user's teams** in a dropdown
- **Allows team switching** by clicking "Switch to" for other teams
- **Provides "Manage Team"** link for current team
- **Provides "Create New Team"** option
- **Highlights current team** with a checkmark

### Team Management Pages

#### Team Creation (`/team`)
- Create new teams
- View all teams user belongs to
- Switch between teams
- Delete teams (if owner)

#### Team Management (`/team/{team_id}/manage`)
- Team settings (name, slug)
- Member management
- Team invitations
- Role management

## ⚙️ Configuration

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

## 🔧 Usage

### Basic Team Operations

```php
// Get user's teams
$teams = auth()->user()->teams;

// Get current team
$currentTeam = auth()->user()->currentTeam;

// Switch to a team
auth()->user()->switchTeam($team);

// Check if user belongs to team
if (auth()->user()->belongsToTeam($team)) {
    // User is a member
}

// Get user's role in team
$role = auth()->user()->getTeamRole($team);
```

### Team Model Operations

```php
// Create a team
$team = Team::create([
    'name' => 'My Team',
    'owner_id' => auth()->id(),
]);

// Add user to team
$team->users()->attach($userId, ['role' => 'member']);

// Check permissions
if ($team->userHasPermission(auth()->id(), 'write')) {
    // User can write to team
}
```

### Middleware

The package includes middleware to ensure users have teams:

```php
Route::middleware(['auth', 'ensure.user.has.team'])->group(function () {
    // Routes that require a team
});
```

## 🎨 Customization

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

### Custom Components

Extend the Livewire components to add custom functionality:

```php
class CustomTeamComponent extends \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\Teams
{
    // Add your custom logic
}
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🆘 Support

If you encounter any issues or have questions:

1. **Check the documentation** in this README
2. **Search existing issues** on GitHub
3. **Create a new issue** with detailed information
4. **Join our community** for discussions and help

---

**Built with ❤️ for the Laravel community**

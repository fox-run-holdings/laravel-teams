# Laravel Teams

A comprehensive team management package for Laravel 12 applications, providing Jetstream-level team functionality with modern Livewire 3 and Flux UI integration.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fox-run-holdings/laravel-teams.svg)](https://packagist.org/packages/fox-run-holdings/laravel-teams)
[![Total Downloads](https://img.shields.io/packagist/dt/fox-run-holdings/laravel-teams.svg)](https://packagist.org/packages/fox-run-holdings/laravel-teams)
[![License](https://img.shields.io/github/license/fox-run-holdings/laravel-teams.svg)](https://github.com/fox-run-holdings/laravel-teams/blob/main/LICENSE.md)

## 🚀 Features

### Core Team Management
- ✅ **Create, edit, and delete teams** with full CRUD operations
- ✅ **Seamless team switching** between multiple teams
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
- ✅ Add teams route to `routes/web.php`
- ✅ Add teams navigation to your header
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

4. **Add the teams route to `routes/web.php`**:
   ```php
   Route::middleware(['auth'])->group(function () {
       Route::get('team/{team_id?}', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\ManageTeamSettings::class)->name('team');
   });
   ```

5. **Add teams navigation to your header** (optional):
   ```blade
   <flux:navbar.item icon="users" :href="route('team')" :current="request()->routeIs('team*')" wire:navigate>
       {{ __('Teams') }}
   </flux:navbar.item>
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

The package provides a simplified routing structure:

### Team Management Routes

- **`/team`** - Teams listing and creation (when no team is selected)
- **`/team/{team_id}`** - Specific team management (when a team is selected)

### URL Examples

- `/team` - View all teams, create new team, or select existing team
- `/team/123` - Manage team with ID 123 (settings, members, invitations)

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

### Livewire Components

The package provides several Livewire components for team management:

- `teams.teams` - Main teams listing and management
- `teams.manage-team-settings` - Team settings management
- `teams.manage-team-members` - Team member management
- `teams.team-invitations` - Team invitation management

### Adding to Settings

Add the teams component to your settings page:

```blade
<livewire:teams.teams />
```

### Team Switching in Navigation

Add team switching to your navigation:

```blade
@if(auth()->user()->teams->count() > 1)
    <div class="relative">
        <flux:button variant="secondary" size="sm">
            {{ auth()->user()->currentTeam->name }}
        </flux:button>
        
        <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg">
            @foreach(auth()->user()->teams as $team)
                <a href="#" 
                   wire:click.prevent="switchTeam({{ $team->id }})"
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    {{ $team->name }}
                </a>
            @endforeach
        </div>
    </div>
@endif
```

## 🔐 Team Roles & Permissions

### Predefined Roles

The package includes four predefined roles with specific permissions:

| Role | Permissions | Description |
|------|-------------|-------------|
| **Owner** | `*` (All) | Full access to all team features |
| **Admin** | `read`, `write`, `delete`, `invite` | Can manage team settings, members, and invitations |
| **Member** | `read`, `write` | Can read and write team content |
| **Viewer** | `read` | Read-only access to team content |

### Permission System

Each permission controls specific actions:

- `read`: View team content and members
- `write`: Create and edit team content
- `delete`: Remove team members and content
- `invite`: Send team invitations

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

## 🛡️ Middleware

The package includes middleware to ensure users have a current team:

```php
// In your routes
Route::middleware(['auth', 'teams'])->group(function () {
    // Your team-aware routes
});
```

## 📁 Database Structure

The package creates the following database tables:

### Teams Table
```sql
teams
├── id (primary key)
├── owner_id (foreign key to users)
├── name (string)
├── slug (unique string)
├── personal_team (boolean)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp, soft deletes)
```

### Team User Pivot Table
```sql
team_user
├── id (primary key)
├── team_id (foreign key to teams)
├── user_id (foreign key to users)
├── role (string)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### Team Invitations Table
```sql
team_invitations
├── id (primary key)
├── team_id (foreign key to teams)
├── email (string)
├── role (string)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### Users Table Extension
```sql
users
└── current_team_id (foreign key to teams, nullable)
```

## 🎨 Views & Components

The package provides Blade views using Flux UI components:

### Available Views
- `laravel-teams::livewire.settings.teams` - Main teams interface
- `laravel-teams::livewire.settings.manage-team-settings` - Team settings
- `laravel-teams::livewire.settings.manage-team-members` - Member management
- `laravel-teams::livewire.settings.team-invitations` - Invitation management

### Customizing Views

You can publish and customize the views:

```bash
php artisan vendor:publish --tag=laravel-teams-views
```

The views will be published to `resources/views/vendor/laravel-teams/`.

## 🔧 API Reference

### Team Model

```php
// Create a new team
$team = Team::create([
    'name' => 'My Team',
    'owner_id' => auth()->id(),
    'personal_team' => false,
]);

// Add a user to a team
$team->users()->attach($userId, ['role' => 'member']);

// Check if user is member
$team->hasUser($userId);

// Check user permissions
$team->userHasPermission($userId, 'write');

// Get team members
$members = $team->users;

// Get team invitations
$invitations = $team->invitations;
```

### User Model Extensions

The package automatically extends your User model with team functionality:

```php
// Get user's teams
$user->teams;

// Get teams user owns
$user->ownedTeams;

// Get current team
$user->currentTeam;

// Get personal team
$user->personalTeam;

// Switch to a different team
$user->switchTeam($team);
```

## 🧪 Testing

The package includes comprehensive tests:

```bash
# Run the test suite
composer test

# Run specific tests
php artisan test --filter=TeamTest
```

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Development Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/fox-run-holdings/laravel-teams.git
   cd laravel-teams
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Run tests**:
   ```bash
   composer test
   ```

4. **Make your changes** and submit a pull request

### Code Style

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Ensure all tests pass before submitting

## 📄 License

The Laravel Teams package is open-sourced software licensed under the [MIT license](LICENSE.md).

## 🙏 Acknowledgments

- Inspired by Laravel Jetstream's team functionality
- Built with Laravel 12's modern architecture
- Uses Livewire 3 for real-time interactions
- Integrated with Flux UI for beautiful interfaces

## 📞 Support

- **Documentation**: [GitHub Wiki](https://github.com/fox-run-holdings/laravel-teams/wiki)
- **Issues**: [GitHub Issues](https://github.com/fox-run-holdings/laravel-teams/issues)
- **Discussions**: [GitHub Discussions](https://github.com/fox-run-holdings/laravel-teams/discussions)

---

**Made with ❤️ by [Fox Run Holdings](https://foxrunholdings.com)**

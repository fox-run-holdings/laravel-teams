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
       Route::get('teams', function () {
           return view('teams');
       })->name('teams');
   });
   ```

5. **Add teams navigation to your header** (optional):
   ```blade
   <flux:navbar.item icon="users" :href="route('teams')" :current="request()->routeIs('teams')" wire:navigate>
       {{ __('Teams') }}
   </flux:navbar.item>
   ```

## ⚙️ Configuration

The package configuration is located in `config/teams.php`. You can customize:

### Basic Configuration
```php
return [
    // Model classes
    'team_model' => \FoxRunHoldings\LaravelTeams\Models\Team::class,
    'team_invitation_model' => \FoxRunHoldings\LaravelTeams\Models\TeamInvitation::class,
    'user_model' => config('auth.providers.users.model'),

    // Features
    'personal_team' => true,
    'invitations' => true,

    // Roles and permissions
    'roles' => [
        'owner' => 'Owner',
        'admin' => 'Admin',
        'member' => 'Member',
        'viewer' => 'Viewer',
    ],

    'permissions' => [
        'owner' => ['*'],
        'admin' => ['read', 'write', 'delete', 'invite'],
        'member' => ['read', 'write'],
        'viewer' => ['read'],
    ],
];
```

## 🎯 Usage

### Basic Team Management

```php
use FoxRunHoldings\LaravelTeams\Models\Team;

// Create a team
$team = Team::create([
    'name' => 'My Team',
    'owner_id' => auth()->id(),
]);

// Add a user to a team
$team->users()->attach($userId, ['role' => 'member']);

// Check if user has permission
if ($team->userHasPermission($user, 'write')) {
    // User can write to team
}

// Switch teams
$user->switchTeam($team);
```

### User Model Extensions

The User model is automatically extended with team functionality:

```php
$user = auth()->user();

// Relationships
$user->teams; // Teams user belongs to
$user->ownedTeams; // Teams user owns
$user->currentTeam; // User's current team
$user->personalTeam; // User's personal team

// Methods
$user->switchTeam($team); // Switch to different team
$user->belongsToTeam($team); // Check team membership
$user->ownsTeam($team); // Check team ownership
$user->getTeamRole($team); // Get user's role in team
```

### Team Model API

```php
use FoxRunHoldings\LaravelTeams\Models\Team;

$team = Team::find(1);

// Relationships
$team->owner; // Team owner
$team->users; // Team members
$team->invitations; // Pending invitations

// Methods
$team->hasUser($user); // Check if user is member
$team->userHasPermission($user, 'write'); // Check permissions
$team->isOwnedBy($user); // Check ownership
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

Publish the views and customize them:

```bash
php artisan vendor:publish --tag=laravel-teams-views
```

## 🔧 Customization

### Custom Team Model

You can extend the Team model:

```php
use FoxRunHoldings\LaravelTeams\Models\Team;

class CustomTeam extends Team
{
    // Add your custom functionality
    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'personal_team',
        'custom_field', // Add custom fields
    ];
}
```

### Custom Roles and Permissions

Modify the configuration in `config/teams.php`:

```php
'roles' => [
    'owner' => 'Owner',
    'admin' => 'Admin',
    'member' => 'Member',
    'viewer' => 'Viewer',
    'moderator' => 'Moderator', // Add custom role
],

'permissions' => [
    'owner' => ['*'],
    'admin' => ['read', 'write', 'delete', 'invite'],
    'member' => ['read', 'write'],
    'viewer' => ['read'],
    'moderator' => ['read', 'write', 'moderate'], // Add custom permissions
],
```

### Custom User Model Extension

The package automatically extends your User model with the `HasTeams` trait. You can customize this behavior:

```php
use FoxRunHoldings\LaravelTeams\Traits\HasTeams;

class User extends Authenticatable
{
    use HasTeams;
    
    // Your custom user functionality
}
```

## 🧪 Testing

The package includes comprehensive tests:

```bash
# Run the package tests
./vendor/bin/phpunit

# Run specific test files
./vendor/bin/phpunit tests/TeamTest.php
```

## 🤝 Contributing

We welcome contributions! Please feel free to submit a Pull Request.

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
   ./vendor/bin/phpunit
   ```

### Contributing Guidelines

- Follow PSR-12 coding standards
- Add tests for new features
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

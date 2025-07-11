# Laravel Teams

A Laravel-based team management system with Livewire components. This package provides a complete team management solution with team creation, switching, and user relationships.

## Installation

1. Install the package via Composer:

```bash
composer require fox-run-holdings/laravel-teams
```

2. Publish the migrations:

```bash
php artisan vendor:publish --tag=laravel-teams-migrations
```

3. Run the migrations:

```bash
php artisan migrate
```

4. Publish the views (optional):

```bash
php artisan vendor:publish --tag=laravel-teams-views
```

## Manual Setup Required

### 1. Add User Model Relationships

Add the following relationships to your `User` model:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use FoxRunHoldings\LaravelTeams\Models\Team;

class User extends Authenticatable
{
    // ... existing code ...

    // Team relationships
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')->withPivot('role')->withTimestamps();
    }

    public function hasTeams()
    {
        return $this->teams()->exists();
    }

    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }
}
```

### 2. Add Routes

Add the following routes to your `routes/web.php` file:

```php
use FoxRunHoldings\LaravelTeams\Livewire\Settings\Team;

Route::middleware(['auth'])->group(function () {
    // Team management routes
    Route::get('settings/team/{team_id?}', Team::class)->name('settings.team');
});
```

## Usage

### Team Switcher Component

Include the team switcher component in your layout:

```blade
<livewire:laravel-teams::settings.team-switcher />
```

### Team Settings Component

Use the team settings component for team management:

```blade
<livewire:laravel-teams::settings.team />
```

Or navigate to the route:

```php
Route::get('settings/team/{team_id?}', Team::class)->name('settings.team');
```

## Features

- **Team Creation**: Create new teams with owners
- **Team Switching**: Switch between teams with a dropdown interface
- **User Relationships**: Many-to-many relationships between users and teams
- **Role Management**: Support for team member roles
- **Current Team Tracking**: Track the user's currently selected team
- **Soft Deletes**: Teams are soft deleted for data integrity

## Database Structure

The package creates the following database structure:

- `teams` table: Stores team information
- `team_user` pivot table: Manages user-team relationships with roles
- `current_team_id` column: Added to users table to track current team

## Configuration

The package uses Laravel's default authentication configuration. Make sure your `config/auth.php` has the correct user model configured.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

For support, please open an issue on GitHub or contact us at info@foxrunholdings.com. 
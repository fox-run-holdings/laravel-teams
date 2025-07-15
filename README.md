# Laravel Teams Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fox-run-holdings/laravel-teams.svg)](https://packagist.org/packages/fox-run-holdings/laravel-teams)
[![Total Downloads](https://img.shields.io/packagist/dt/fox-run-holdings/laravel-teams.svg)](https://packagist.org/packages/fox-run-holdings/laravel-teams)
[![License](https://img.shields.io/packagist/l/fox-run-holdings/laravel-teams.svg)](https://packagist.org/packages/fox-run-holdings/laravel-teams)

A comprehensive Laravel package for team management with invitations and role-based access control.

## Features

- **Team Management**: Create, edit, and manage teams
- **Role-Based Access Control**: Owner, Admin, and Member roles
- **Team Invitations**: Send and manage team invitations
- **Team Switching**: Switch between teams seamlessly
- **Livewire Components**: Modern, reactive UI components
- **Soft Deletes**: Safe team deletion with soft deletes
- **Pagination**: Built-in pagination for team listings

## Installation

### Prerequisites

This package requires **Flux UI Pro** to be installed in your Laravel application. The views and components use Flux UI Pro components.

If you haven't installed Flux UI Pro yet, please install it first:

```bash
composer require flux-ui/flux-ui-pro
```

### 1. Install the package

```bash
composer require fox-run-holdings/laravel-teams
```

### 2. Publish the configuration and views

```bash
php artisan vendor:publish --provider="FoxRunHoldings\LaravelTeams\Providers\TeamsServiceProvider"
```

### 3. Run the migrations

```bash
php artisan migrate
```

### 4. Add team relationships to your User model

Add the following methods to your `App\Models\User` model:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use FoxRunHoldings\LaravelTeams\Models\Team;
use FoxRunHoldings\LaravelTeams\Models\TeamInvitation;

class User extends Authenticatable
{
    // ... existing code ...

    /**
     * Get the teams that the user belongs to.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the team invitations for the user.
     */
    public function Invitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class, 'email', 'email');
    }

    /**
     * Get the current team of the user.
     */
    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    /**
     * Check if the user has teams.
     */
    public function HasTeams(): bool
    {
        return $this->teams()->exists();
    }

    /**
     * Get the user's role in a specific team.
     */
    public function role($team_id): string
    {
        $team = $this->teams()->where('team_id', $team_id)->first();
        return $team ? $team->pivot->role : 'member';
    }

    /**
     * Get the user's initials for avatar display.
     */
    public function initials(): string
    {
        $name = explode(' ', $this->name);
        $initials = '';
        
        if (count($name) >= 2) {
            $initials = strtoupper(substr($name[0], 0, 1) . substr($name[1], 0, 1));
        } else {
            $initials = strtoupper(substr($this->name, 0, 2));
        }
        
        return $initials;
    }
}
```

## Usage

### Facade Usage

The package provides a facade for easy access to team functionality:

```php
use FoxRunHoldings\LaravelTeams\Facades\Teams;

// Create a team
$team = Teams::create([
    'name' => 'My Team',
    'owner_id' => auth()->id(),
]);

// Find a team
$team = Teams::find(1);

// Create an invitation
$invitation = Teams::invite($team->id, 'user@example.com', 'member');

// Accept an invitation
Teams::acceptInvitation($invitation->id);

// Switch to a team
Teams::switchTeam($team->id);

// Get current team
$currentTeam = Teams::getCurrentTeam();
```

### Livewire Components

The package provides several Livewire components that you can use in your views:

#### Team Invitations
```blade
<livewire:teams.invitations />
```

#### Team Management
```blade
<livewire:teams.manage />
```

#### Team Members
```blade
<livewire:teams.members />
```

#### Team Switcher
```blade
<livewire:teams.switcher />
```

### Routes

Add the following routes to your `routes/web.php`:

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/teams', function () {
        return view('teams.manage');
    })->name('teams');
    
    Route::get('/teams/members', function () {
        return view('teams.members');
    })->name('teams.members');
    
    Route::get('/teams/invitations', function () {
        return view('teams.invitations');
    })->name('teams.invitations');
});
```

### Configuration

The package configuration is published to `config/teams.php`. You can customize:

- User model class
- Available team roles
- Invitation statuses
- Pagination settings
- Route configuration

## Database Structure

The package creates the following tables:

- `teams` - Team information
- `team_user` - Many-to-many relationship between teams and users
- `team_invitations` - Team invitations
- Adds `current_team_id` to the `users` table

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please open an issue on GitHub or contact us at info@foxrunholdings.com. 
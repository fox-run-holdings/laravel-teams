<?php

namespace FoxRunHoldings\LaravelTeams\Extensions;

use FoxRunHoldings\LaravelTeams\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserModelExtension
{
    public static function extend($userClass)
    {
        // Add team relationships using macros
        $userClass::macro('teams', function () {
            return $this->belongsToMany(Team::class, 'team_user')
                ->withPivot('role')
                ->withTimestamps();
        });

        $userClass::macro('ownedTeams', function () {
            return $this->hasMany(Team::class, 'owner_id');
        });

        $userClass::macro('currentTeam', function () {
            return $this->belongsTo(Team::class, 'current_team_id');
        });

        $userClass::macro('personalTeam', function () {
            return $this->ownedTeams()->where('personal_team', true)->first();
        });

        $userClass::macro('switchTeam', function ($team) {
            if (!$this->teams()->where('team_id', $team->id)->exists()) {
                throw new \Exception('User does not belong to this team.');
            }

            $this->update(['current_team_id' => $team->id]);
        });

        $userClass::macro('belongsToTeam', function ($team) {
            if (is_numeric($team)) {
                return $this->teams()->where('team_id', $team)->exists();
            }
            
            return $this->teams()->where('team_id', $team->id)->exists();
        });

        $userClass::macro('ownsTeam', function ($team) {
            if (is_numeric($team)) {
                return $this->ownedTeams()->where('id', $team)->exists();
            }
            
            return $this->ownedTeams()->where('id', $team->id)->exists();
        });

        $userClass::macro('getTeamRole', function ($team) {
            if (is_numeric($team)) {
                $pivot = $this->teams()->where('team_id', $team)->first()?->pivot;
            } else {
                $pivot = $this->teams()->where('team_id', $team->id)->first()?->pivot;
            }
            
            return $pivot?->role;
        });
    }
} 
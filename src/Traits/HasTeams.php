<?php

namespace FoxRunHoldings\LaravelTeams\Traits;

use FoxRunHoldings\LaravelTeams\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTeams
{
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
     * Get the teams that the user owns.
     */
    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    /**
     * Get the user's current team.
     */
    public function currentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    /**
     * Get the user's personal team.
     */
    public function personalTeam()
    {
        return $this->ownedTeams()->where('personal_team', true)->first();
    }

    /**
     * Switch to the given team.
     */
    public function switchTeam($team)
    {
        if (!$this->teams()->where('team_id', $team->id)->exists()) {
            throw new \Exception('User does not belong to this team.');
        }

        $this->update(['current_team_id' => $team->id]);
    }

    /**
     * Check if user belongs to a specific team.
     */
    public function belongsToTeam($team): bool
    {
        if (is_numeric($team)) {
            return $this->teams()->where('team_id', $team)->exists();
        }
        
        return $this->teams()->where('team_id', $team->id)->exists();
    }

    /**
     * Check if user owns a specific team.
     */
    public function ownsTeam($team): bool
    {
        if (is_numeric($team)) {
            return $this->ownedTeams()->where('id', $team)->exists();
        }
        
        return $this->ownedTeams()->where('id', $team->id)->exists();
    }

    /**
     * Get the user's role in a specific team.
     */
    public function getTeamRole($team): ?string
    {
        if (is_numeric($team)) {
            $pivot = $this->teams()->where('team_id', $team)->first()?->pivot;
        } else {
            $pivot = $this->teams()->where('team_id', $team->id)->first()?->pivot;
        }
        
        return $pivot?->role;
    }
} 
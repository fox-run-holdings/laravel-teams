<?php

namespace FoxRunHoldings\LaravelTeams\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Team extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'owner_id',
    ];

    /**
     * Get the owner of the team.
     */
    public function owner(): BelongsTo
    {
        $userModel = Config::get('teams.user_model');
        return $this->belongsTo($userModel, 'owner_id');
    }

    /**
     * Get the users that belong to the team.
     */
    public function users(): BelongsToMany
    {
        $userModel = Config::get('teams.user_model');
        return $this->belongsToMany($userModel, 'team_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the invitations for the team.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
    }
} 
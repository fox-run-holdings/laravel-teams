<?php

namespace FoxRunHoldings\LaravelTeams\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;

class TeamInvitation extends Model
{
    protected $fillable = [
        'team_id',
        'invited_by_user_id',
        'email',
        'role',
        'status',
    ];

    protected $attributes = [
        'role' => 'member',
        'status' => 'pending',
    ];

    /**
     * Get the team that the invitation belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user who sent the invitation.
     */
    public function invitedBy(): BelongsTo
    {
        $userModel = Config::get('teams.user_model');
        return $this->belongsTo($userModel, 'invited_by_user_id');
    }

    /**
     * Get the user who was invited (by email).
     */
    public function user(): BelongsTo
    {
        $userModel = Config::get('teams.user_model');
        return $this->belongsTo($userModel, 'email', 'email');
    }
} 
<?php

namespace FoxRunHoldings\LaravelTeams;

use FoxRunHoldings\LaravelTeams\Models\Team;
use FoxRunHoldings\LaravelTeams\Models\TeamInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Teams
{
    /**
     * Create a new team.
     */
    public function create(array $attributes): Team
    {
        return Team::create($attributes);
    }

    /**
     * Find a team by ID.
     */
    public function find($id): ?Team
    {
        return Team::find($id);
    }

    /**
     * Find a team by ID or throw an exception.
     */
    public function findOrFail($id): Team
    {
        return Team::findOrFail($id);
    }

    /**
     * Get all teams.
     */
    public function all()
    {
        return Team::all();
    }

    /**
     * Get the query builder for teams.
     */
    public function query()
    {
        return Team::query();
    }

    /**
     * Create a team invitation.
     */
    public function invite($teamId, $email, $role = 'member', $invitedBy = null): TeamInvitation
    {
        $invitedBy = $invitedBy ?? Auth::id();

        return TeamInvitation::create([
            'team_id' => $teamId,
            'invited_by_user_id' => $invitedBy,
            'email' => $email,
            'role' => $role,
            'status' => 'pending',
        ]);
    }

    /**
     * Accept a team invitation.
     */
    public function acceptInvitation($invitationId): bool
    {
        $invitation = TeamInvitation::findOrFail($invitationId);
        $userModel = Config::get('teams.user_model');
        $user = $userModel::where('email', $invitation->email)->first();

        if (!$user) {
            return false;
        }

        // Check if user is already a member
        if ($invitation->team->users()->where('user_id', $user->id)->exists()) {
            return false;
        }

        // Add user to team
        $invitation->team->users()->attach($user->id, ['role' => $invitation->role]);

        // Set as current team if user doesn't have one
        if (!$user->current_team_id) {
            $user->current_team_id = $invitation->team_id;
            $user->save();
        }

        // Update invitation status
        $invitation->status = 'accepted';
        $invitation->save();

        return true;
    }

    /**
     * Decline a team invitation.
     */
    public function declineInvitation($invitationId): bool
    {
        $invitation = TeamInvitation::findOrFail($invitationId);
        $invitation->status = 'declined';
        $invitation->save();

        return true;
    }

    /**
     * Get user's current team.
     */
    public function getCurrentTeam($user = null)
    {
        $user = $user ?? Auth::user();
        return $user ? $user->currentTeam : null;
    }

    /**
     * Switch user's current team.
     */
    public function switchTeam($teamId, $user = null): bool
    {
        $user = $user ?? Auth::user();
        
        if (!$user) {
            return false;
        }

        // Check if user is a member of the team
        if (!$user->teams()->where('team_id', $teamId)->exists()) {
            return false;
        }

        $user->current_team_id = $teamId;
        $user->save();

        return true;
    }
} 
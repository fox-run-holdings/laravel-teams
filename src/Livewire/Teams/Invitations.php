<?php

namespace FoxRunHoldings\LaravelTeams\Livewire\Teams;

use FoxRunHoldings\LaravelTeams\Models\TeamInvitation;
use Illuminate\Support\Facades\Config;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Invitations extends Component
{
    #[Computed]
    public function invitations()
    {
        $userModel = Config::get('teams.user_model');
        return $userModel::find(auth()->user()->id)
            ->Invitations()
            ->where('status', 'pending')
            ->paginate(config('teams.pagination.per_page', 5));
    }

    #[Computed]
    public function archivedInvitations()
    {
        $userModel = Config::get('teams.user_model');
        return $userModel::find(auth()->user()->id)
            ->Invitations()
            ->paginate(config('teams.pagination.per_page', 5));
    }

    public function acceptInvitation($id)
    {
        $userModel = Config::get('teams.user_model');
        $invitation = $userModel::find(auth()->user()->id)
            ->Invitations()
            ->findOrFail($id);

        if (empty($invitation)) {
            session()->flash('error', 'Invitation no longer exists.');
            return;
        }

        if ($invitation->team->users()->where('user_id', auth()->user()->id)->exists()) {
            session()->flash('error', 'You are already a member of this team.');
            return;
        }

        $invitation->team->users()->attach(auth()->user()->id, ['role' => $invitation->role]);
        auth()->user()->current_team_id = $invitation->team_id;
        auth()->user()->save();
        $invitation->status = 'accepted';
        $invitation->save();

        session()->flash('success', 'Invitation accepted successfully.');
    }

    public function render()
    {
        return view('laravel-teams::livewire.teams.invitations');
    }
} 
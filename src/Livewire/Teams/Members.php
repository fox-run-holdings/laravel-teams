<?php

namespace FoxRunHoldings\LaravelTeams\Livewire\Teams;

use FoxRunHoldings\LaravelTeams\Models\Team;
use FoxRunHoldings\LaravelTeams\Models\TeamInvitation;
use Illuminate\Support\Facades\Config;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Members extends Component
{
    use WithPagination;

    /**
     * The ID of the team to manage.
     */
    public ?int $team_id = null;
    public Team $team;
    public string $new_member_email = '', $new_member_role = 'member';
    public array $member_roles = [];

    public function mount(?int $team_id = null)
    {
        $this->team_id = $team_id;
        if ($team_id) {
            $this->team = Team::findOrFail($team_id);
            $this->member_roles = $this->team->users->pluck('pivot.role', 'id')->toArray();
        } else {
            $this->team = new Team();
        }
    }

    #[Computed]
    public function members()
    {
        $userModel = Config::get('teams.user_model');
        return $userModel::whereHas('teams', function ($query) {
            $query->where('team_id', $this->team_id);
        })->paginate(config('teams.pagination.per_page', 5));
    }

    #[Computed]
    public function invitations()
    {
        return TeamInvitation::where('team_id', $this->team_id)
            ->where('status', 'pending')
            ->paginate(config('teams.pagination.per_page', 5));
    }

    public function inviteMember()
    {
        $new_member_email = $this->validate([
            'new_member_email' => 'required|email',
        ])['new_member_email'];

        if ($this->team->users->contains('email', $new_member_email)) {
            session()->flash('error', 'This user is already a member of the team.');
            return;
        }

        $this->team->invitations()->create([
            'email' => $new_member_email,
            'invited_by_user_id' => auth()->user()->id,
            'role' => $this->new_member_role,
        ]);

        session()->flash('success', 'Invitation sent successfully.');
    }

    public function cancelInvitation($invitation_id)
    {
        $invitation = TeamInvitation::findOrFail($invitation_id);
        if ($invitation->team_id !== $this->team_id) {
            session()->flash('error', 'This invitation does not belong to the current team.');
            return;
        }
        $invitation->delete();

        session()->flash('success', 'Invitation cancelled successfully.');
    }

    public function changeRole($user_id, $role)
    {
        // if user_id is current user and there are no other owners, do not allow changing role
        if ($user_id == auth()->user()->id && $this->team->users()->where('role', 'owner')->count() <= 1) {
            session()->flash('error', 'You cannot change your own role if you are the only owner of the team.');
            return;
        }
        $this->team->users()->updateExistingPivot($user_id, ['role' => $role]);
        $this->member_roles[$user_id] = $role;
    }

    public function render()
    {
        return view('laravel-teams::livewire.teams.members');
    }
} 
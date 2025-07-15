<?php

namespace FoxRunHoldings\LaravelTeams\Livewire\Teams;

use FoxRunHoldings\LaravelTeams\Models\Team;
use Livewire\Component;

class Manage extends Component
{
    /**
     * The ID of the team to manage.
     */
    public ?int $team_id = null;
    public Team $team;
    public string $team_name;

    public function mount(?int $team_id = null)
    {
        $this->team_id = $team_id;
        if ($team_id) {
            $this->team = Team::findOrFail($team_id);
            $this->team_name = $this->team->name;
        } else {
            $this->team = new Team();
        }
    }

    public function saveTeam()
    {
        $this->validate([
            'team_name' => 'required|string|max:255',
        ]);

        if (empty($this->team->id)) {
            $this->team->name = $this->team_name;
            $this->team->owner_id = auth()->id();
            $this->team->save();
            $this->team->users()->attach(auth()->id(), ['role' => 'owner']);
            $this->team->refresh();
            $user = auth()->user();
            $user->current_team_id = $this->team->id;
            $user->save();
        } else {
            $this->team->name = $this->team_name;
            $this->team->save();
        }

        $this->team->refresh();

        session()->flash('success', 'Team saved successfully.');
    }

    public function render()
    {
        $teams = auth()->user()->teams;
        return view('laravel-teams::livewire.teams.manage', ['teams' => $teams]);
    }
} 
<?php

namespace FoxRunHoldings\LaravelTeams\Livewire\Settings;

use FoxRunHoldings\LaravelTeams\Models\Team as TeamModel;
use Livewire\Component;

class Team extends Component
{
    /**
     * The ID of the team to manage.
     */
    public ?int $team_id = null;
    public TeamModel $team;
    public string $team_name = '';

    public function mount(?int $team_id = null)
    {
        $this->team_id = $team_id;
        if ($team_id) {
            $this->team = TeamModel::findOrFail($team_id);
        } else {
            $this->team = new TeamModel();
        }
    }

    public function createTeam()
    {
        $this->validate([
            'team_name' => 'required|string|max:255',
        ]);

        $this->team->name = $this->team_name;
        $this->team->owner_id = auth()->id();
        $this->team->save();

        session()->flash('message', 'Team created successfully.');
        return redirect()->route('settings.team', ['team_id' => $this->team->id]);
    }

    public function render()
    {
        $teams = auth()->user()->teams;
        return view('laravel-teams::settings.team', ['teams' => $teams]);
    }
} 
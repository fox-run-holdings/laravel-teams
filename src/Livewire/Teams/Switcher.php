<?php

namespace FoxRunHoldings\LaravelTeams\Livewire\Teams;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Switcher extends Component
{
    public function switchTeam($team_id)
    {
        $user = auth()->user();
        $user->current_team_id = $team_id;
        $user->save();
        return redirect('/dashboard');
    }

    public function openTeamManagement()
    {
        return redirect()->route('teams', ['team_id' => auth()->user()->currentTeam]);
    }

    public function render()
    {
        return view('laravel-teams::livewire.teams.switcher');
    }
} 
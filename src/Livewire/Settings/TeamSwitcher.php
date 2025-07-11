<?php

namespace FoxRunHoldings\LaravelTeams\Livewire\Settings;

use Livewire\Component;

class TeamSwitcher extends Component
{
    public function switchTeam($team_id)
    {
        $user = auth()->user();
        $user->current_team_id = $team_id;
        $user->save();
        return redirect('/dashboard');
    }

    public function render()
    {
        return view('laravel-teams::settings.team-switcher');
    }
} 
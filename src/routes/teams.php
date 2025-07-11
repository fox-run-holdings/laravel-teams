<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'ensure.user.has.team'])->group(function () {
    Route::get('team/{team_id?}', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\Teams::class)->name('team');
    Route::get('team/{team_id}/manage', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\ManageTeamSettings::class)->name('team.manage');
});
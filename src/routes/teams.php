<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('team/{team?}', \FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams\ManageTeamSettings::class)->name('team');
});
<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Team management routes
    Route::prefix('teams')->name('teams.')->group(function () {
        Route::get('/', function () {
            return view('laravel-teams::teams.index');
        })->name('index');
        
        Route::get('/{team}', function (\FoxRunHoldings\LaravelTeams\Models\Team $team) {
            return view('laravel-teams::teams.show', compact('team'));
        })->name('show');
        
        Route::get('/{team}/settings', function (\FoxRunHoldings\LaravelTeams\Models\Team $team) {
            return view('laravel-teams::teams.settings', compact('team'));
        })->name('settings');
        
        Route::get('/{team}/members', function (\FoxRunHoldings\LaravelTeams\Models\Team $team) {
            return view('laravel-teams::teams.members', compact('team'));
        })->name('members');
        
        Route::get('/{team}/invitations', function (\FoxRunHoldings\LaravelTeams\Models\Team $team) {
            return view('laravel-teams::teams.invitations', compact('team'));
        })->name('invitations');
    });
}); 
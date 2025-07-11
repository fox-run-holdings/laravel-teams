<?php

namespace FoxRunHoldings\LaravelTeams\Tests;

use App\Models\User;
use FoxRunHoldings\LaravelTeams\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomaticInstallationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_model_has_team_relationships()
    {
        $user = User::factory()->create();
        
        // Test that the teams relationship exists
        $this->assertTrue(method_exists($user, 'teams'));
        $this->assertTrue(method_exists($user, 'ownedTeams'));
        $this->assertTrue(method_exists($user, 'currentTeam'));
        $this->assertTrue(method_exists($user, 'personalTeam'));
        $this->assertTrue(method_exists($user, 'switchTeam'));
    }
    
    public function test_can_create_team_and_add_user()
    {
        $user = User::factory()->create();
        
        $team = Team::create([
            'name' => 'Test Team',
            'owner_id' => $user->id,
        ]);
        
        // Add user to team
        $team->users()->attach($user->id, ['role' => 'owner']);
        
        // Test user can access teams
        $this->assertTrue($user->teams()->exists());
        $this->assertTrue($user->belongsToTeam($team));
        $this->assertTrue($user->ownsTeam($team));
    }
    
    public function test_user_fillable_includes_current_team_id()
    {
        $user = new User();
        $fillable = $user->getFillable();
        
        $this->assertContains('current_team_id', $fillable);
    }
    
    public function test_installation_command_exists()
    {
        $this->artisan('list')->assertExitCode(0);
        
        $this->artisan('list')
            ->expectsOutputToContain('teams:install')
            ->assertExitCode(0);
    }
} 
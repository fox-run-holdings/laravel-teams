<?php

namespace FoxRunHoldings\LaravelTeams\Tests;

use App\Models\User;
use FoxRunHoldings\LaravelTeams\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_team()
    {
        $user = User::factory()->create();
        
        $team = Team::create([
            'name' => 'Test Team',
            'owner_id' => $user->id,
        ]);
        
        $this->assertDatabaseHas('teams', [
            'name' => 'Test Team',
            'owner_id' => $user->id,
        ]);
        
        $this->assertEquals($user->id, $team->owner_id);
    }
    
    public function test_can_add_user_to_team()
    {
        $user = User::factory()->create();
        $team = Team::create([
            'name' => 'Test Team',
            'owner_id' => $user->id,
        ]);
        
        $member = User::factory()->create();
        $team->users()->attach($member->id, ['role' => 'member']);
        
        $this->assertTrue($team->hasUser($member->id));
        $this->assertEquals('member', $team->users()->where('user_id', $member->id)->first()->pivot->role);
    }
    
    public function test_user_has_permission()
    {
        $user = User::factory()->create();
        $team = Team::create([
            'name' => 'Test Team',
            'owner_id' => $user->id,
        ]);
        
        // Owner should have all permissions
        $this->assertTrue($team->userHasPermission($user->id, 'write'));
        $this->assertTrue($team->userHasPermission($user->id, 'delete'));
        $this->assertTrue($team->userHasPermission($user->id, 'invite'));
    }
    
    public function test_team_owner_can_delete_team()
    {
        $user = User::factory()->create();
        $team = Team::create([
            'name' => 'Test Team',
            'owner_id' => $user->id,
        ]);
        
        $this->assertTrue($team->isOwnedBy($user->id));
    }
} 
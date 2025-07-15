<?php

namespace FoxRunHoldings\LaravelTeams\Tests;

use FoxRunHoldings\LaravelTeams\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_team()
    {
        $team = Team::create([
            'name' => 'Test Team',
            'owner_id' => 1,
        ]);

        $this->assertInstanceOf(Team::class, $team);
        $this->assertEquals('Test Team', $team->name);
        $this->assertEquals(1, $team->owner_id);
    }

    public function test_team_has_soft_deletes()
    {
        $team = Team::create([
            'name' => 'Test Team',
            'owner_id' => 1,
        ]);

        $team->delete();

        $this->assertSoftDeleted($team);
    }
} 
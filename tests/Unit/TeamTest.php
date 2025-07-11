<?php

namespace FoxRunHoldings\LaravelTeams\Tests\Unit;

use FoxRunHoldings\LaravelTeams\Models\Team;
use FoxRunHoldings\LaravelTeams\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_team()
    {
        $team = Team::create([
            'name' => 'Test Team',
            'owner_id' => 1,
        ]);

        $this->assertEquals('Test Team', $team->name);
        $this->assertEquals(1, $team->owner_id);
    }

    /** @test */
    public function it_has_soft_deletes()
    {
        $team = Team::create([
            'name' => 'Test Team',
            'owner_id' => 1,
        ]);

        $team->delete();

        $this->assertSoftDeleted($team);
    }
} 
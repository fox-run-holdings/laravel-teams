<?php

namespace FoxRunHoldings\LaravelTeams\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \FoxRunHoldings\LaravelTeams\Models\Team create(array $attributes)
 * @method static \FoxRunHoldings\LaravelTeams\Models\Team find($id)
 * @method static \FoxRunHoldings\LaravelTeams\Models\Team findOrFail($id)
 * @method static \Illuminate\Database\Eloquent\Collection all()
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * 
 * @see \FoxRunHoldings\LaravelTeams\Teams
 */
class Teams extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-teams';
    }
} 
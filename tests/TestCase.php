<?php

namespace FoxRunHoldings\LaravelTeams\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use FoxRunHoldings\LaravelTeams\Providers\TeamsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            TeamsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
} 
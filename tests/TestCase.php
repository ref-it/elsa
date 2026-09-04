<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Guard against running the suite on a live database.
     *
     * Feature tests use RefreshDatabase, which migrates from scratch and would
     * therefore wipe whatever database it is pointed at. This runs just before
     * the trait does, while the application exists but no migration has run.
     */
    protected function setUpTraits()
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");

        if ($database !== ':memory:' && ! str_contains($database, 'test')) {
            throw new RuntimeException(
                "Refusing to run tests against database [{$database}] on connection [{$connection}]: "
                .'the test database name must contain "test". Check DB_DATABASE in phpunit.xml.'
            );
        }

        return parent::setUpTraits();
    }
}

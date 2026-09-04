<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Feature tests boot the full application and run against a fresh in-memory
| SQLite database (see phpunit.xml). Unit tests stay free of both.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| Custom expectations belong here, e.g.:
| expect()->extend('toBeCurrentElection', fn () => ...);
|
*/

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

/**
 * Create a user that passes the "admin" and "election-commission" gates.
 */
function adminUser(): User
{
    return User::factory()
        ->inGroups([config('app.group_admin')])
        ->create();
}

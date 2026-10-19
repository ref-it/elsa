<?php

use App\Support\Robots;
use Tests\TestCase;

uses(TestCase::class);

it('allows indexing for the general information pages', function (string $routeName): void {
    expect(Robots::allowsIndexing($routeName))->toBeTrue();
})->with(['home', 'public-infos', 'public-committee', 'imprint', 'privacy', 'accessibility']);

it('denies indexing everywhere else', function (?string $routeName): void {
    expect(Robots::allowsIndexing($routeName))->toBeFalse();
})->with([
    'candidate list' => 'public-candidates',
    'candidate profile' => 'public-candidate',
    'results' => 'public-results',
    'candidacy' => 'candidacy',
    'own candidacy' => 'candidacy-my',
    'admin dashboard' => 'admin-dashboard',
    'admin candidates' => 'admin-candidates-index',
    'login' => 'login',
    'unnamed route' => null,
]);

it('hands out a directive outside of any request', function (): void {
    expect(Robots::currentDirective())->toBe('noindex, nofollow');
});

<?php

use App\Support\Robots;
use Illuminate\Support\Facades\DB;

beforeEach(function (): void {
    DB::table('elections')->insert([
        'id' => 1,
        'name' => json_encode(['Wahl 2026', 'Election 2026']),
        'infotext' => json_encode(['Infos DE', 'Infos EN']),
        'public' => true,
        'candidates_exist' => true,
        'all_votes_counted' => false,
    ]);

    DB::table('committees')->insert([
        'id' => 1,
        'name' => json_encode(['Gremium', 'Committee']),
        'description' => json_encode(['Beschreibung', 'Description']),
        'seats' => 5,
        'seats_deputy' => 5,
        'elections' => json_encode([1]),
        'lists' => false,
        'lists_quoted' => false,
        'active' => true,
        'priority' => 1,
    ]);

    DB::table('legal_texts')->insert([
        'id' => 1,
        'imprint' => json_encode(['Impressum DE', 'Imprint EN']),
        'privacy' => json_encode(['Datenschutz DE', 'Privacy EN']),
        'accessibility' => json_encode(['Barrierefreiheit DE', 'Accessibility EN']),
    ]);
});

it('leaves the general information pages indexable', function (string $route, array $parameters): void {
    $this->get(route($route, $parameters))
        ->assertOk()
        ->assertHeaderMissing('X-Robots-Tag')
        ->assertDontSee('name="robots"', false);
})->with([
    'election infos' => ['public-infos', ['election' => 1]],
    'committee infos' => ['public-committee', ['election' => 1, 'id' => 1]],
    'imprint' => ['imprint', []],
    'privacy' => ['privacy', []],
    'accessibility' => ['accessibility', []],
]);

it('keeps the pages with personal data out of search indexes', function (string $route, array $parameters): void {
    $this->get(route($route, $parameters))
        ->assertOk()
        ->assertHeader('X-Robots-Tag', Robots::DIRECTIVE)
        ->assertSee('<meta name="robots" content="'.Robots::DIRECTIVE.'">', false);
})->with([
    'candidate list' => ['public-candidates', ['election' => 1, 'committee' => 1]],
    'results' => ['public-results', ['election' => 1, 'committee' => 1]],
]);

it('keeps the admin pages out of search indexes', function (): void {
    $this->actingAs(adminUser())
        ->get(route('admin-elections-index'))
        ->assertOk()
        ->assertHeader('X-Robots-Tag', Robots::DIRECTIVE)
        ->assertSee('<meta name="robots" content="'.Robots::DIRECTIVE.'">', false);
});

it('sends the header on responses without a page body', function (): void {
    $this->get(route('language', ['language' => 'en']))
        ->assertRedirect()
        ->assertHeader('X-Robots-Tag', Robots::DIRECTIVE);
});

<?php

use Illuminate\Support\Facades\DB;

beforeEach(function (): void {
    DB::table('legal_texts')->insert([
        'id' => 1,
        'imprint' => json_encode(['Impressum DE', 'Imprint EN']),
        'privacy' => json_encode(['Datenschutz DE', 'Privacy EN']),
        'accessibility' => json_encode(['Barrierefreiheit DE', 'Accessibility EN']),
    ]);
});

it('renders the imprint page', function (): void {
    $this->get(route('imprint'))->assertOk();
});

it('renders the privacy page', function (): void {
    $this->get(route('privacy'))->assertOk();
});

it('renders the accessibility page', function (): void {
    $this->get(route('accessibility'))->assertOk();
});

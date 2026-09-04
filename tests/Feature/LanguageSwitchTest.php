<?php

it('stores the chosen language in the session', function (): void {
    $this->get(route('language', ['language' => 'en']))
        ->assertRedirect()
        ->assertSessionHas('language', 'en');
});

it('applies the chosen language to the following request', function (): void {
    $this->get(route('language', ['language' => 'en']));
    $this->get(route('language', ['language' => 'en']));

    expect(app()->getLocale())->toBe('en');
});

it('defaults to german for a fresh session', function (): void {
    $this->get(route('language', ['language' => 'de']));

    expect(app()->getLocale())->toBe('de');
});

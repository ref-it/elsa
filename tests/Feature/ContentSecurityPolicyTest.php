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

it('sends a policy with every page', function (): void {
    $response = $this->get(route('imprint'))->assertOk();

    expect($response->headers->get('Content-Security-Policy'))
        ->toContain("default-src 'self'")
        ->toContain("frame-ancestors 'none'");
});

it('reports instead of blocking when asked to', function (): void {
    config(['csp.report_only' => true]);

    $this->get(route('imprint'))
        ->assertOk()
        ->assertHeaderMissing('Content-Security-Policy')
        ->assertHeader('Content-Security-Policy-Report-Only');
});

it('sends no policy at all when switched off', function (): void {
    config(['csp.enabled' => false]);

    $this->get(route('imprint'))
        ->assertOk()
        ->assertHeaderMissing('Content-Security-Policy')
        ->assertHeaderMissing('Content-Security-Policy-Report-Only');
});

/*
 * The policy allows inline scripts by nonce only, so a <script> or <style>
 * tag that misses one - because a package stopped picking the nonce up, say -
 * is silently dropped by the browser. Catch that here instead.
 */
it('puts the nonce on every script and style tag it renders', function (): void {
    $response = $this->get(route('imprint'))->assertOk();

    preg_match('/\'nonce-([^\']+)\'/', (string) $response->headers->get('Content-Security-Policy'), $matches);

    expect($matches)->toHaveKey(1, 'the policy carries a nonce');

    preg_match_all('/<(script|style)\b[^>]*>/i', $response->getContent(), $tags);

    expect($tags[0])->not->toBeEmpty();

    foreach ($tags[0] as $tag) {
        expect($tag)->toContain('nonce="'.$matches[1].'"');
    }
});

<?php

use App\Support\ContentSecurityPolicy;
use Tests\TestCase;

uses(TestCase::class);

/**
 * Pulls one directive out of the policy, e.g. "script-src 'self' ...".
 */
function directive(string $name, string $policy): string
{
    $directives = collect(explode('; ', $policy))
        ->mapWithKeys(fn (string $directive): array => [strtok($directive, ' ') => $directive]);

    expect($directives)->toHaveKey($name);

    return $directives[$name];
}

it('lets inline scripts through by nonce only', function (): void {
    $scripts = directive('script-src', ContentSecurityPolicy::header('abc123'));

    expect($scripts)->toContain("'nonce-abc123'")
        ->and($scripts)->not->toContain("'unsafe-inline'");
});

it('keeps the application out of frames and off foreign form targets', function (): void {
    $policy = ContentSecurityPolicy::header('abc123');

    expect(directive('frame-ancestors', $policy))->toBe("frame-ancestors 'none'")
        ->and(directive('form-action', $policy))->toBe("form-action 'self'")
        ->and(directive('base-uri', $policy))->toBe("base-uri 'self'")
        ->and(directive('object-src', $policy))->toBe("object-src 'none'");
});

it('allows the picture sources the cropper needs', function (): void {
    expect(directive('img-src', ContentSecurityPolicy::header('abc123')))
        ->toContain('data:')
        ->toContain('blob:');
});

it('upgrades insecure requests only over https', function (): void {
    expect(ContentSecurityPolicy::header('abc123', secure: true))
        ->toContain('upgrade-insecure-requests');

    expect(ContentSecurityPolicy::header('abc123', secure: false))
        ->not->toContain('upgrade-insecure-requests');
});

it('reports to the configured collector', function (): void {
    config(['csp.report_uri' => 'https://example.test/csp']);

    expect(directive('report-uri', ContentSecurityPolicy::header('abc123')))
        ->toBe('report-uri https://example.test/csp');
});

it('leaves the vite dev server out of a deployed policy', function (): void {
    expect(ContentSecurityPolicy::header('abc123'))->not->toContain('localhost:5173');
});

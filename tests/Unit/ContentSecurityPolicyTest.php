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

it('allows the oidc provider to serve the profile pictures', function (): void {
    config(['services.openidconnect.base_url' => 'https://auth.example.test/realms/elsa']);

    // The path is dropped: a CSP source matches on scheme, host and port.
    expect(directive('img-src', ContentSecurityPolicy::header('abc123')))
        ->toContain('https://auth.example.test')
        ->not->toContain('/realms/elsa');
});

it('keeps the provider port when there is one', function (): void {
    config(['services.openidconnect.base_url' => 'http://localhost:8080/realms/elsa']);

    expect(directive('img-src', ContentSecurityPolicy::header('abc123')))
        ->toContain('http://localhost:8080');
});

it('copes with an unconfigured provider', function (): void {
    config(['services.openidconnect.base_url' => null]);

    expect(directive('img-src', ContentSecurityPolicy::header('abc123')))
        ->toBe("img-src 'self' data: blob:");
});

it('adds the configured image hosts', function (): void {
    config([
        'csp.image_sources' => ['https://cdn.example.test'],
        'services.openidconnect.base_url' => 'https://auth.example.test',
    ]);

    expect(directive('img-src', ContentSecurityPolicy::header('abc123')))
        ->toContain('https://cdn.example.test')
        ->toContain('https://auth.example.test');
});

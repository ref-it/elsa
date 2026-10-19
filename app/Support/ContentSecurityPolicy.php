<?php

namespace App\Support;

use Illuminate\Support\Facades\Vite;

class ContentSecurityPolicy
{
    /**
     * Builds the policy for the current request.
     *
     * The nonce is the one Vite::useCspNonce() handed out; Livewire, Flux and
     * the @vite directive all stamp it onto the tags they emit, so no inline
     * script needs 'unsafe-inline'.
     */
    public static function header(?string $nonce = null, bool $secure = false): string
    {
        $nonce ??= Vite::cspNonce();

        $script = ["'self'"];

        if ($nonce !== null && $nonce !== '') {
            $script[] = "'nonce-{$nonce}'";
        }

        // Alpine - which both Livewire and Flux build on - compiles the
        // expressions in x-show, x-on and friends with `new Function`, so the
        // policy cannot do without 'unsafe-eval'. Livewire does ship a
        // CSP-safe Alpine (config/livewire.php, "csp_safe"), but it only
        // understands property access and method calls, which neither our own
        // templates nor the Flux components keep to.
        $script[] = "'unsafe-eval'";

        $devServer = static::viteDevServer();

        $directives = [
            'default-src' => ["'self'"],
            'base-uri' => ["'self'"],
            'object-src' => ["'none'"],
            // The application is never meant to be framed - that rules out
            // clickjacking, and replaces X-Frame-Options.
            'frame-ancestors' => ["'none'"],
            'form-action' => ["'self'"],
            'script-src' => [...$script, ...$devServer],
            // Livewire, Flux and cropperjs each build <style> elements at
            // runtime. Those never pass through Blade, so a nonce cannot reach
            // them and inline styles have to stay allowed. Stylesheets proper
            // are still limited to our own origin.
            'style-src' => ["'self'", "'unsafe-inline'", ...$devServer],
            // data: for the cropped picture the browser hands back, blob: for
            // the preview of a freshly picked file.
            'img-src' => ["'self'", 'data:', 'blob:'],
            'font-src' => ["'self'"],
            'connect-src' => ["'self'", ...$devServer, ...static::viteDevSocket()],
            'worker-src' => ["'self'", 'blob:'],
        ];

        if ($secure) {
            $directives['upgrade-insecure-requests'] = [];
        }

        if ($reportUri = config('csp.report_uri')) {
            $directives['report-uri'] = [$reportUri];
        }

        return collect($directives)
            ->map(fn (array $sources, string $directive): string => trim($directive.' '.implode(' ', $sources)))
            ->implode('; ');
    }

    /**
     * While `npm run dev` is running the assets come from Vite's dev server
     * instead of from public/build, so it has to be allowed in - but only
     * then, never in a deployed application.
     */
    protected static function viteDevServer(): array
    {
        $url = static::viteDevServerUrl();

        return $url === null ? [] : [$url];
    }

    /**
     * Vite's hot reload talks over a websocket on the same host.
     */
    protected static function viteDevSocket(): array
    {
        $url = static::viteDevServerUrl();

        return $url === null ? [] : [str_replace(['https://', 'http://'], ['wss://', 'ws://'], $url)];
    }

    protected static function viteDevServerUrl(): ?string
    {
        if (! Vite::isRunningHot()) {
            return null;
        }

        $url = trim((string) file_get_contents(public_path('hot')));

        return $url === '' ? null : rtrim($url, '/');
    }
}

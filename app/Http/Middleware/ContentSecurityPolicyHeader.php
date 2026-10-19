<?php

namespace App\Http\Middleware;

use App\Support\ContentSecurityPolicy;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicyHeader
{
    /**
     * Hands out this request's nonce before anything renders - Livewire, Flux
     * and the @vite directive read it back from Vite::cspNonce() - and sends
     * the matching policy with the response.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = Vite::useCspNonce();

        // The Flux directives take their nonce as an argument; unlike
        // Livewire they do not fall back to Vite::cspNonce() on their own.
        view()->share('cspNonce', $nonce);

        $response = $next($request);

        if (config('csp.enabled')) {
            $header = config('csp.report_only')
                ? 'Content-Security-Policy-Report-Only'
                : 'Content-Security-Policy';

            $response->headers->set($header, ContentSecurityPolicy::header($nonce, $request->isSecure()));
        }

        return $response;
    }
}

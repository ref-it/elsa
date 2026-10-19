<?php

namespace App\Http\Middleware;

use App\Support\Robots;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RobotsHeader
{
    /**
     * Repeats the layouts' robots <meta> tag as a header, which also covers
     * the responses that carry no <head>: redirects, files, Livewire updates.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $directive = Robots::currentDirective();

        if ($directive !== null) {
            $response->headers->set('X-Robots-Tag', $directive);
        }

        return $response;
    }
}

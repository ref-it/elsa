<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class Robots
{
    /**
     * What pages that are kept out of search indexes tell crawlers.
     */
    public const DIRECTIVE = 'noindex, nofollow';

    /**
     * The directive for the route currently being handled, or null for the
     * pages search engines may index (see config/seo.php).
     */
    public static function currentDirective(): ?string
    {
        return self::allowsIndexing(Route::currentRouteName()) ? null : self::DIRECTIVE;
    }

    /**
     * Unnamed routes - the redirects into the current election, for one - are
     * not indexable either, so a null name simply fails the lookup.
     */
    public static function allowsIndexing(?string $routeName): bool
    {
        return in_array($routeName, config('seo.indexable_routes', []), true);
    }
}

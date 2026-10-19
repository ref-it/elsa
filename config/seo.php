<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Indexable Routes
    |--------------------------------------------------------------------------
    |
    | Only the general information about an election belongs in a search
    | index. Every other route - above all the candidate, candidacy and result
    | pages, which carry personal data - is served with a "noindex, nofollow",
    | both as a <meta> tag (see the layouts) and as an X-Robots-Tag header.
    |
    */

    'indexable_routes' => [
        'home',
        'public-infos',
        'public-committee',
        'imprint',
        'privacy',
        'accessibility',
    ],

];

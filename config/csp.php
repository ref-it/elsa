<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | The policy itself lives in App\Support\ContentSecurityPolicy, where each
    | directive is explained next to the thing that needs it. Set
    | CSP_REPORT_ONLY=true to have browsers report violations without blocking
    | anything - useful after a change to the front end, together with
    | CSP_REPORT_URI if you have somewhere to collect the reports.
    |
    */

    'enabled' => env('CSP_ENABLED', true),

    'report_only' => env('CSP_REPORT_ONLY', false),

    'report_uri' => env('CSP_REPORT_URI'),

    /*
    |--------------------------------------------------------------------------
    | Additional Image Sources
    |--------------------------------------------------------------------------
    |
    | Besides our own origin, images may be loaded from the OIDC provider,
    | which serves the users' profile pictures - that one is derived from
    | services.openidconnect.base_url and needs no entry here. Any further
    | host goes into CSP_IMAGE_SOURCES, comma separated:
    |
    |     CSP_IMAGE_SOURCES="https://cdn.example.org,https://pics.example.org"
    |
    */

    'image_sources' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('CSP_IMAGE_SOURCES', ''))
    ))),

];

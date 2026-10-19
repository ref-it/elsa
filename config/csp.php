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

];

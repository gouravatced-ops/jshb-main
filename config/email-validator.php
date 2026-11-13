<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Checks to Run
    |--------------------------------------------------------------------------
    |
    | Toggle individual validation layers. Order matters: a failing layer
    | short-circuits the remaining checks.
    |
    */

    'checks' => [
        'syntax'     => true,
        'mx'         => true,
        'disposable' => true,
        'smtp'       => env('EMAIL_VALIDATOR_SMTP', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMTP Handshake
    |--------------------------------------------------------------------------
    |
    | Settings for the SMTP RCPT-TO probe. Many ISPs block outbound port 25,
    | so this layer is best run from a server with port 25 open. The
    | from_email is used in the MAIL FROM step of the handshake.
    |
    */

    'smtp' => [
        'timeout'    => 10,
        'port'       => 25,
        'helo_host'  => env('EMAIL_VALIDATOR_HELO', 'localhost'),
        'from_email' => env('EMAIL_VALIDATOR_FROM', 'verify@example.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | DNS Resolver
    |--------------------------------------------------------------------------
    */

    'dns' => [
        'timeout' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Cache results to avoid repeatedly probing the same address. Set
    | enabled to false to bypass entirely.
    |
    */

    'cache' => [
        'enabled' => true,
        'store'   => env('EMAIL_VALIDATOR_CACHE_STORE', null),
        'ttl'     => 60 * 60 * 24, // 24 hours
        'prefix'  => 'email_validator:',
    ],

    /*
    |--------------------------------------------------------------------------
    | Disposable Domains
    |--------------------------------------------------------------------------
    |
    | A path to a newline-separated list of disposable domains. The package
    | ships a small default list; override with your own for broader coverage.
    |
    */

    'disposable' => [
        'list_path' => resource_path('email-validator/disposable-domains.txt'),
        'extra'     => [],
    ],

];

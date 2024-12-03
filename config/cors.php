<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | Here you can specify which paths should be allowed to accept cross-origin
    | requests. You can use wildcards to match multiple paths. The default is
    | "api/*" which will allow any API routes to accept cross-origin requests.
    |
    */

    'paths' => ['api/*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | You can specify which HTTP methods are allowed for cross-origin requests.
    | You can use a wildcard "*" to allow all methods or a list of specific
    | methods, such as "GET", "POST", "PUT", etc.
    |
    */

    'allowed_methods' => ['*'], // Permite toate metodele HTTP

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | This defines which origins (URLs) are allowed to access your API. You can
    | allow specific domains or use "*" to allow all domains (this is less secure).
    |
    */

    'allowed_origins' => [
        'http://localhost:8000',  // De exemplu, pentru aplicația ta Flutter (sau frontend-ul)
        'http://127.0.0.1:8000',
        'http://192.168.0.84:8000'
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | Specifies which headers are allowed when making a request.
    | You can use "*" to allow all headers.
    |
    */

    'allowed_headers' => ['*'], // Permite toate antetele HTTP

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | Specifies which headers should be exposed to the browser. This controls
    | what headers are available in the response when making a cross-origin
    | request.
    |
    */

    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | Defines how long the results of a preflight request (the request made before
    | the actual request to check CORS) can be cached.
    |
    */

    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | If true, the browser will include cookies in the request when making
    | cross-origin requests.
    |
    */

    'supports_credentials' => false,
];

<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Grupurile de middleware ale aplicației.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // Middleware pentru aplicații web
            \App\Http\Middleware\VerifyCsrfToken::class,

        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            //\App\Http\Middleware\CorsMiddleware::class, // Înregistrează middleware-ul CORS
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Middleware-ul rutei.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'cors' => \App\Http\Middleware\CorsMiddleware::class, // Asigură că middleware-ul CORS este disponibil
    ];
}

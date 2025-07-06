<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareAliases = [
        // middleware lain...
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}

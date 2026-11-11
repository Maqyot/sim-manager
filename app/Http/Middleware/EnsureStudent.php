<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isStudent() && $request->user()->student, 403, 'Student access required.');

        return $next($request);
    }
}

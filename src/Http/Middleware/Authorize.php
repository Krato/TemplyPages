<?php

namespace Infinety\TemplyPages\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Infinety\TemplyPages\TemplyPagesTool;
use Symfony\Component\HttpFoundation\Response;

class Authorize
{
    /**
     * @param Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return app(TemplyPagesTool::class)->authorize($request)
        ? $next($request)
        : abort(403);
    }
}

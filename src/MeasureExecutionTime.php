<?php

namespace Middlewares;

use Closure;
use Illuminate\Http\Request;

class MeasureExecutionTime
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (config('app.env') === 'testing') {
            return $response;
        }

        $executionTime = microtime(true) - LUMEN_START;
        $response->header('X-Elapsed-Time', $executionTime);

        return $response;
    }
}

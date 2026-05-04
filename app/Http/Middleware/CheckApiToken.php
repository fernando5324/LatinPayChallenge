<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken() !== config('app.api_token')) {
            return response()->json([
                'message' => 'Invalid token',
                'data' => []
            ], 401);
        }

        return $next($request);
    }
}


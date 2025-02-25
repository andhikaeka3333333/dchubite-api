<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user() && auth()->user()->email === 'dchubite@gmail.com') {
            return $next($request);
        }
        return response()->json(['message' => 'Unauthorized'], 403);
    }
}

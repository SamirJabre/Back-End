<?php
// app/Http/Middleware/JwtAuthMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Exception;

class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        try {
            $credentials = JWT::decode($token, new Key('your-secret-key', 'HS256'));
        } catch (Exception $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $request->merge(['auth' => $credentials]);

        return $next($request);
    }
}
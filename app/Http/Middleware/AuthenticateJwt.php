<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateJwt
{
    public function __construct(private readonly JwtService $jwt) {}

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?? $request->cookie(config('jwt.cookie', 'access_token'));

        $user = is_string($token) ? $this->jwt->resolveUser($token) : null;

        if (! $user) {
            abort(401, 'Invalid or expired token.');
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}

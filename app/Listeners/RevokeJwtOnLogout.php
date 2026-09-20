<?php

namespace App\Listeners;

use App\Models\LoginLog;
use App\Services\JwtService;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cookie;

class RevokeJwtOnLogout
{
    public function __construct(private readonly JwtService $jwt) {}

    public function handle(Logout $event): void
    {
        $token = request()->cookie(config('jwt.cookie', 'access_token'));

        if (is_string($token)) {
            $payload = $this->jwt->decode($token);

            if ($payload && isset($payload->jti)) {
                LoginLog::query()
                    ->where('jti', $payload->jti)
                    ->update(['revoked_at' => now()]);
            }
        }

        Cookie::queue(Cookie::forget(config('jwt.cookie', 'access_token')));
    }
}

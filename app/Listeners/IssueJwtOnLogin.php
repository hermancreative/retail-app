<?php

namespace App\Listeners;

use App\Models\LoginLog;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cookie;

class IssueJwtOnLogin
{
    public function __construct(private readonly JwtService $jwt) {}

    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $issued = $this->jwt->issue($user);

        LoginLog::create([
            'user_id' => $user->id,
            'jti' => $issued['jti'],
            'ip_address' => request()->ip(),
            'user_agent' => (string) request()->userAgent(),
            'expires_at' => $issued['expires_at'],
        ]);

        Cookie::queue(Cookie::make(
            name: config('jwt.cookie', 'access_token'),
            value: $issued['token'],
            minutes: (int) config('jwt.ttl', 60),
            path: '/',
            domain: null,
            secure: app()->environment('production'),
            httpOnly: true,
            raw: false,
            sameSite: 'lax',
        ));
    }
}

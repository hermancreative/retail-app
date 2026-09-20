<?php

namespace App\Services;

use App\Models\LoginLog;
use App\Models\User;
use Carbon\CarbonImmutable;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;
use stdClass;

class JwtService
{
    /**
     * Issue a signed JWT for the given user.
     *
     * @return array{token: string, jti: string, expires_at: CarbonImmutable}
     */
    public function issue(User $user): array
    {
        $ttl = (int) config('jwt.ttl', 60);
        $now = now();
        $expiresAt = $now->clone()->addMinutes($ttl);
        $jti = (string) Str::uuid();

        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'jti' => $jti,
            'iat' => $now->timestamp,
            'nbf' => $now->timestamp,
            'exp' => $expiresAt->timestamp,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $token = JWT::encode($payload, config('jwt.secret'), config('jwt.algo', 'HS256'));

        return [
            'token' => $token,
            'jti' => $jti,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * Decode and validate a JWT, returning its payload.
     */
    public function decode(string $token): ?stdClass
    {
        try {
            return JWT::decode($token, new Key(config('jwt.secret'), config('jwt.algo', 'HS256')));
        } catch (ExpiredException) {
            return null;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Resolve the authenticated user represented by a JWT, if valid and
     * not revoked.
     */
    public function resolveUser(string $token): ?User
    {
        $payload = $this->decode($token);

        if (! $payload || ! isset($payload->sub, $payload->jti)) {
            return null;
        }

        $isRevoked = LoginLog::query()
            ->where('jti', $payload->jti)
            ->whereNotNull('revoked_at')
            ->exists();

        if ($isRevoked) {
            return null;
        }

        return User::find((int) $payload->sub);
    }
}

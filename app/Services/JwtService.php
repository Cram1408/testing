<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

class JwtService
{
    protected string $secret;
    protected string $algo;
    protected int $ttl;
    protected int $refreshTtl;

    public function __construct()
    {
        $rawSecret = config('jwt.secret', config('app.key'));
        // Clean base64: prefix if present from Laravel APP_KEY
        if (str_starts_with($rawSecret, 'base64:')) {
            $rawSecret = base64_decode(substr($rawSecret, 7)) ?: $rawSecret;
        }

        $this->secret     = $rawSecret;
        $this->algo       = config('jwt.algo', 'HS256');
        $this->ttl        = (int) config('jwt.ttl', 3600);
        $this->refreshTtl = (int) config('jwt.refresh_ttl', 1209600);
    }

    /**
     * Generate an access token for the given user.
     */
    public function generateToken(User $user, array $extraClaims = []): string
    {
        $now = time();
        $payload = array_merge([
            'iss'   => config('app.url', 'http://localhost:8001'),
            'sub'   => $user->id,
            'email' => $user->email,
            'name'  => $user->name,
            'role'  => $user->role,
            'type'  => 'access',
            'iat'   => $now,
            'nbf'   => $now,
            'exp'   => $now + $this->ttl,
            'jti'   => (string) Str::uuid(),
        ], $extraClaims);

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    /**
     * Generate a refresh token for the given user.
     */
    public function generateRefreshToken(User $user): string
    {
        $now = time();
        $payload = [
            'iss'  => config('app.url', 'http://localhost:8001'),
            'sub'  => $user->id,
            'type' => 'refresh',
            'iat'  => $now,
            'nbf'  => $now,
            'exp'  => $now + $this->refreshTtl,
            'jti'  => (string) Str::uuid(),
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    /**
     * Generate a complete authentication token pair.
     */
    public function generateTokenPair(User $user): array
    {
        $accessToken  = $this->generateToken($user);
        $refreshToken = $this->generateRefreshToken($user);

        return [
            'token_type'    => 'Bearer',
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in'    => $this->ttl,
            'user'          => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'role'        => $user->role,
                'phone'       => $user->phone,
                'avatar'      => $user->avatar,
            ],
        ];
    }

    /**
     * Decode and verify a JWT string.
     *
     * @return array{success: bool, payload?: object, error?: string, code?: string}
     */
    public function decode(string $token): array
    {
        try {
            $payload = JWT::decode($token, new Key($this->secret, $this->algo));
            return [
                'success' => true,
                'payload' => $payload,
            ];
        } catch (ExpiredException $e) {
            return [
                'success' => false,
                'error'   => 'Token has expired.',
                'code'    => 'token_expired',
            ];
        } catch (SignatureInvalidException $e) {
            return [
                'success' => false,
                'error'   => 'Invalid token signature.',
                'code'    => 'invalid_signature',
            ];
        } catch (BeforeValidException $e) {
            return [
                'success' => false,
                'error'   => 'Token is not yet active.',
                'code'    => 'token_premature',
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'error'   => 'Malformed or invalid token: ' . $e->getMessage(),
                'code'    => 'token_invalid',
            ];
        }
    }

    /**
     * Validate token, check revocation, and fetch the corresponding User model.
     *
     * @return array{valid: bool, user?: User, payload?: object, error?: string, code?: string}
     */
    public function validate(string $token, string $expectedType = 'access'): array
    {
        $decoded = $this->decode($token);
        if (! $decoded['success']) {
            return [
                'valid' => false,
                'error' => $decoded['error'],
                'code'  => $decoded['code'],
            ];
        }

        $payload = $decoded['payload'];

        // Verify token type (access vs refresh)
        if (($payload->type ?? 'access') !== $expectedType) {
            return [
                'valid' => false,
                'error' => "Invalid token type. Expected {$expectedType} token.",
                'code'  => 'invalid_token_type',
            ];
        }

        // Check if token has been revoked / blacklisted
        if (! empty($payload->jti) && $this->isRevoked($payload->jti)) {
            return [
                'valid' => false,
                'error' => 'Token has been revoked.',
                'code'  => 'token_revoked',
            ];
        }

        // Find associated user
        $user = User::find($payload->sub);
        if (! $user) {
            return [
                'valid' => false,
                'error' => 'User associated with this token no longer exists.',
                'code'  => 'user_not_found',
            ];
        }

        return [
            'valid'   => true,
            'user'    => $user,
            'payload' => $payload,
        ];
    }

    /**
     * Revoke (blacklist) a token until its expiration time.
     */
    public function revoke(string $token): bool
    {
        $decoded = $this->decode($token);
        if (! $decoded['success']) {
            return false;
        }

        $payload = $decoded['payload'];
        if (empty($payload->jti)) {
            return false;
        }

        $remainingSeconds = max(60, ($payload->exp ?? time()) - time());
        Cache::put('jwt_revoked_' . $payload->jti, true, $remainingSeconds);

        return true;
    }

    /**
     * Check if a token ID (jti) is in the revocation blacklist.
     */
    public function isRevoked(string $jti): bool
    {
        return Cache::has('jwt_revoked_' . $jti);
    }

    /**
     * Issue a new token pair using a valid refresh token (Token Rotation).
     *
     * @return array{success: bool, data?: array, error?: string, code?: string}
     */
    public function refreshToken(string $refreshToken): array
    {
        $validation = $this->validate($refreshToken, 'refresh');
        if (! $validation['valid']) {
            return [
                'success' => false,
                'error'   => $validation['error'],
                'code'    => $validation['code'],
            ];
        }

        // Revoke the used refresh token for strict security (Refresh Token Rotation)
        $this->revoke($refreshToken);

        // Issue new token pair
        $newTokens = $this->generateTokenPair($validation['user']);

        return [
            'success' => true,
            'data'    => $newTokens,
        ];
    }
}


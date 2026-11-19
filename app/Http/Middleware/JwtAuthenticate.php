<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthenticate
{
    public function __construct(protected JwtService $jwtService) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->extractToken($request);

        if (! $token) {
            return $this->unauthorizedResponse($request, 'Authentication token not provided. Please log in.', 'token_missing');
        }

        $result = $this->jwtService->validate($token, 'access');

        if (! $result['valid']) {
            return $this->unauthorizedResponse($request, $result['error'] ?? 'Unauthorized access.', $result['code'] ?? 'unauthorized');
        }

        // Authenticate the user for the current request
        Auth::setUser($result['user']);
        $request->setUserResolver(fn () => $result['user']);
        $request->attributes->set('jwt_payload', $result['payload']);
        $request->attributes->set('jwt_token', $token);

        return $next($request);
    }

    /**
     * Extract token from Authorization header, query parameter, or cookie.
     */
    protected function extractToken(Request $request): ?string
    {
        // 1. Authorization header: Bearer <token>
        $header = $request->header('Authorization', '');
        if (preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return trim($matches[1]);
        }

        // 2. Query string or body param
        if ($request->has('token')) {
            return $request->input('token');
        }

        // 3. Cookie fallback
        if ($request->hasCookie('jwt_token')) {
            return $request->cookie('jwt_token');
        }

        return null;
    }

    /**
     * Build appropriate unauthorized response based on request format.
     */
    protected function unauthorizedResponse(Request $request, string $message, string $code): Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status'  => 'error',
                'code'    => $code,
                'message' => $message,
            ], Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->route('login')->with('error', $message);
    }
}


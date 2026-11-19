<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthController extends Controller
{
    public function __construct(protected JwtService $jwtService) {}

    /**
     * User registration with JWT token generation.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
            'phone' => [
                'required',
                'digits:10',
                'regex:/^[6-9]\d{9}$/',
            ],
            'address'     => 'nullable|string|max:255',
            'city'        => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'state'       => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'postal_code' => ['nullable', 'digits:6', 'regex:/^\d{6}$/'],
            'avatar'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required'        => 'Full name is required.',
            'name.regex'           => 'Full name may only contain alphabetic characters and spaces.',
            'email.required'       => 'Email address is required.',
            'email.email'          => 'Please provide a valid email address.',
            'email.unique'         => 'An account with this email address already exists.',
            'password.required'    => 'Password is required.',
            'password.min'         => 'Password must be at least 6 characters.',
            'password.confirmed'   => 'Password confirmation does not match.',
            'phone.required'       => 'Phone number is required.',
            'phone.digits'         => 'Phone number must contain exactly 10 digits.',
            'phone.regex'          => 'Phone number must be a valid 10-digit number starting with 6–9.',
            'city.regex'           => 'City may only contain alphabetic characters and spaces.',
            'state.regex'          => 'State may only contain alphabetic characters and spaces.',
            'postal_code.digits'   => 'PIN code must contain exactly 6 digits.',
            'postal_code.regex'    => 'PIN code must contain exactly 6 digits.',
            'avatar.image'         => 'The profile picture must be an image file.',
            'avatar.mimes'         => 'The profile picture must be a JPG, PNG, or WEBP image.',
            'avatar.max'           => 'The profile picture size must not exceed 2MB.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => strtolower($validated['email']),
            'password'          => Hash::make($validated['password']),
            'role'              => 'customer',
            'phone'             => $validated['phone'],
            'avatar'            => $avatarPath,
            'address'           => $validated['address'] ?? null,
            'city'              => $validated['city'] ?? null,
            'state'             => $validated['state'] ?? null,
            'postal_code'       => $validated['postal_code'] ?? null,
            'email_verified_at' => now(),
        ]);

        $tokenData = $this->jwtService->generateTokenPair($user);

        return response()->json([
            'status'  => 'success',
            'message' => 'Account created successfully! You are authenticated.',
            'data'    => $tokenData,
        ], Response::HTTP_CREATED);
    }

    /**
     * User login with credential validation and JWT token issuance.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email address is required.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        $user = User::where('email', strtolower($credentials['email']))->first();

        if (! $user || ! $user->password || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status'  => 'error',
                'code'    => 'invalid_credentials',
                'message' => 'The provided email or password does not match our records.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $tokenData = $this->jwtService->generateTokenPair($user);

        return response()->json([
            'status'  => 'success',
            'message' => 'Authentication successful.',
            'data'    => $tokenData,
        ]);
    }

    /**
     * Get authenticated user profile and token details.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $payload = $request->attributes->get('jwt_payload');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'user'    => $user,
                'token'   => [
                    'jti'        => $payload->jti ?? null,
                    'issued_at'  => isset($payload->iat) ? date('Y-m-d H:i:s', $payload->iat) : null,
                    'expires_at' => isset($payload->exp) ? date('Y-m-d H:i:s', $payload->exp) : null,
                ],
            ],
        ]);
    }

    /**
     * Refresh access token using a valid refresh token (Token Rotation).
     */
    public function refresh(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'refresh_token' => 'required|string',
        ], [
            'refresh_token.required' => 'Refresh token is required.',
        ]);

        $result = $this->jwtService->refreshToken($validated['refresh_token']);

        if (! $result['success']) {
            return response()->json([
                'status'  => 'error',
                'code'    => $result['code'] ?? 'token_refresh_failed',
                'message' => $result['error'] ?? 'Unable to refresh token.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Token refreshed successfully.',
            'data'    => $result['data'],
        ]);
    }

    /**
     * Invalidate (revoke) access token and log out.
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->attributes->get('jwt_token');

        if ($token) {
            $this->jwtService->revoke($token);
        }

        // Also revoke refresh token if supplied in the request body
        if ($request->has('refresh_token')) {
            $this->jwtService->revoke($request->input('refresh_token'));
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Successfully logged out. Your token has been revoked.',
        ]);
    }
}


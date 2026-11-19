<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    /**
     * Send / Resend OTP to a given phone number.
     * Called via AJAX (returns JSON).
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone'   => ['required', 'regex:/^[6-9]\d{9}$/'],
            'purpose' => ['sometimes', 'in:registration,login,password_reset'],
        ]);

        $phone   = $request->input('phone');
        $purpose = $request->input('purpose', 'registration');

        // For password reset, ensure account with this phone exists
        if ($purpose === 'password_reset') {
            $userExists = \App\Models\User::where('phone', $phone)->exists();
            if (! $userExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'No account found with this mobile number. Please check the number or register.',
                ], 404);
            }
        }

        if ($this->otpService->isOnCooldown($phone, $purpose)) {
            $remaining = $this->otpService->cooldownSecondsRemaining($phone, $purpose);
            return response()->json([
                'success'   => false,
                'message'   => "Please wait {$remaining} second(s) before requesting a new OTP.",
                'cooldown'  => $remaining,
            ], 429);
        }

        try {
            $result = $this->otpService->send($phone, $purpose);
            $response = [
                'success'    => true,
                'message'    => "OTP sent to +91 {$phone}. Valid for 10 minutes.",
                'expires_at' => $result['expires_at']->toIso8601String(),
                'cooldown'   => OtpService::RESEND_COOLDOWN,
            ];

            if (app()->environment('local')) {
                $response['dev_otp'] = $result['otp'];
            }

            return response()->json($response);
        } catch (\Throwable $e) {
            Log::error('OTP send failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
            ], 500);
        }
    }

    /**
     * Verify OTP entered by the user.
     * Called via AJAX (returns JSON).
     */
    public function verify(Request $request)
    {
        $request->validate([
            'phone'             => ['required', 'regex:/^[6-9]\d{9}$/'],
            'otp'               => ['nullable', 'string'],
            'purpose'           => ['sometimes', 'in:registration,login,password_reset'],
            'firebase_verified' => ['sometimes', 'boolean'],
        ]);

        $phone   = $request->input('phone');
        $otp     = $request->input('otp');
        $purpose = $request->input('purpose', 'registration');

        // Verified directly via Firebase Phone Auth
        if ($request->boolean('firebase_verified')) {
            $request->session()->put("otp_verified_{$purpose}_{$phone}", true);
            return response()->json([
                'success' => true,
                'message' => 'Mobile number verified successfully via Firebase!',
            ]);
        }

        if (empty($otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter the 6-digit OTP code.',
            ], 422);
        }

        $result = $this->otpService->verify($phone, $otp, $purpose);

        if ($result['success']) {
            // Store a session flag so the registration/update controller can trust the phone
            $request->session()->put("otp_verified_{$purpose}_{$phone}", true);
        }

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}

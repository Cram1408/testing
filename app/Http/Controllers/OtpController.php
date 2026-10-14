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
            'purpose' => ['sometimes', 'in:registration,login'],
        ]);

        $phone   = $request->input('phone');
        $purpose = $request->input('purpose', 'registration');

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
            return response()->json([
                'success'    => true,
                'message'    => "OTP sent to +91 {$phone}. Valid for 10 minutes.",
                'expires_at' => $result['expires_at']->toIso8601String(),
                'cooldown'   => OtpService::RESEND_COOLDOWN,
            ]);
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
            'phone'   => ['required', 'regex:/^[6-9]\d{9}$/'],
            'otp'     => ['required', 'digits:6'],
            'purpose' => ['sometimes', 'in:registration,login'],
        ]);

        $phone   = $request->input('phone');
        $otp     = $request->input('otp');
        $purpose = $request->input('purpose', 'registration');

        $result = $this->otpService->verify($phone, $otp, $purpose);

        if ($result['success']) {
            // Store a session flag so the registration/update controller can trust the phone
            $request->session()->put("otp_verified_{$purpose}_{$phone}", true);
        }

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}

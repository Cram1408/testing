<?php

namespace App\Services;

use App\Models\OtpVerification;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /** OTP validity window in minutes */
    const EXPIRY_MINUTES = 10;

    /** Cooldown between resend requests in seconds */
    const RESEND_COOLDOWN = 60;

    /**
     * Generate and "send" a new OTP for the given phone number.
     * Marks any older active OTPs for this phone + purpose as used.
     *
     * @return array{otp: string, expires_at: \Carbon\Carbon}
     */
    public function send(string $phone, string $purpose = 'registration'): array
    {
        // Invalidate any previous unused OTPs for this phone + purpose
        OtpVerification::where('phone', $phone)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        $otp = $this->generateOtp();
        $expiresAt = now()->addMinutes(self::EXPIRY_MINUTES);

        OtpVerification::create([
            'phone'      => $phone,
            'otp'        => $otp,
            'purpose'    => $purpose,
            'is_used'    => false,
            'attempts'   => 0,
            'expires_at' => $expiresAt,
        ]);

        // In production, integrate with an SMS gateway (e.g., Twilio / MSG91).
        // For development, we log it so you can read it from storage/logs/laravel.log.
        Log::info("[OTP] Phone: {$phone} | Code: {$otp} | Purpose: {$purpose} | Expires: {$expiresAt}");

        return ['otp' => $otp, 'expires_at' => $expiresAt];
    }

    /**
     * Verify the OTP submitted by the user.
     *
     * @return array{success: bool, message: string}
     */
    public function verify(string $phone, string $enteredOtp, string $purpose = 'registration'): array
    {
        $record = OtpVerification::where('phone', $phone)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (! $record) {
            return ['success' => false, 'message' => 'No OTP found. Please request a new one.'];
        }

        if ($record->expires_at->isPast()) {
            $record->update(['is_used' => true]);
            return ['success' => false, 'message' => 'OTP has expired. Please request a new one.'];
        }

        // Increment attempt counter
        $record->increment('attempts');

        if ($record->attempts > 5) {
            $record->update(['is_used' => true]);
            return ['success' => false, 'message' => 'Too many failed attempts. Please request a new OTP.'];
        }

        if ($record->otp !== $enteredOtp) {
            $remaining = 5 - $record->attempts;
            return [
                'success' => false,
                'message' => "Incorrect OTP. {$remaining} attempt(s) remaining.",
            ];
        }

        // Mark OTP as used
        $record->update(['is_used' => true]);

        return ['success' => true, 'message' => 'OTP verified successfully!'];
    }

    /**
     * Check if a resend is currently on cooldown for this phone + purpose.
     */
    public function isOnCooldown(string $phone, string $purpose = 'registration'): bool
    {
        $latest = OtpVerification::where('phone', $phone)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if (! $latest) {
            return false;
        }

        return $latest->created_at->diffInSeconds(now()) < self::RESEND_COOLDOWN;
    }

    /**
     * Seconds remaining in the resend cooldown.
     */
    public function cooldownSecondsRemaining(string $phone, string $purpose = 'registration'): int
    {
        $latest = OtpVerification::where('phone', $phone)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if (! $latest) {
            return 0;
        }

        $elapsed = $latest->created_at->diffInSeconds(now());
        return max(0, self::RESEND_COOLDOWN - $elapsed);
    }

    /** Generate a cryptographically random 6-digit OTP */
    private function generateOtp(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
}

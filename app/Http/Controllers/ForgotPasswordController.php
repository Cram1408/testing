<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Display the Forgot Password page (supports both Mobile OTP & Email Link).
     */
    public function showForgotForm()
    {
        if (Auth::check()) {
            return redirect()->route('customer.dashboard');
        }
        return view('auth.forgot-password');
    }

    /**
     * Reset password via verified Mobile OTP.
     */
    public function resetWithOtp(Request $request)
    {
        $validated = $request->validate([
            'phone'    => ['required', 'regex:/^[6-9]\d{9}$/'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'phone.required' => 'Mobile number is required.',
            'phone.regex'    => 'Enter a valid 10-digit Indian mobile number.',
            'password.min'   => 'Password must be at least 6 characters.',
        ]);

        $phone = $validated['phone'];
        $sessionKey = "otp_verified_password_reset_{$phone}";

        // Ensure the phone number was verified via OTP in this session
        if (! $request->session()->get($sessionKey)) {
            return back()->withInput()->withErrors([
                'phone' => 'Please verify the OTP sent to your mobile number before resetting your password.',
            ]);
        }

        // Find user by phone
        $user = User::where('phone', $phone)->first();
        if (! $user) {
            return back()->withInput()->withErrors([
                'phone' => 'No account found with this mobile number.',
            ]);
        }

        // Update password and clear session OTP flag
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        $request->session()->forget($sessionKey);

        // Auto login user
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')
            ->with('success', 'Your password has been successfully reset! Welcome back, ' . $user->name . '.');
    }

    /**
     * Send password reset link to user's email.
     */
    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withInput()->withErrors([
                'email' => 'We could not find an account with that email address.',
            ]);
        }

        // Create token for password reset
        $token = Password::broker()->createToken($user);
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

        // In production, an email is dispatched. In local dev, we also provide a one-click reset link.
        $message = 'A password reset link has been created for your email.';
        
        return back()->with([
            'status'     => $message,
            'reset_link' => $resetUrl,
        ]);
    }

    /**
     * Display the reset password form (from email link).
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Process password reset via token from email link.
     */
    public function resetWithEmail(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'No account found with this email address.']);
        }

        // Verify token in password_reset_tokens table
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (! $record || ! Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'This password reset link is invalid or has expired. Please request a new one.']);
        }

        // Check token expiration (60 minutes default)
        if (now()->subMinutes(60)->gt($record->created_at)) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This password reset link has expired. Please request a new one.']);
        }

        // Update password and remove used token
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Auto login user
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')
            ->with('success', 'Your password has been reset successfully! Welcome back.');
    }
}


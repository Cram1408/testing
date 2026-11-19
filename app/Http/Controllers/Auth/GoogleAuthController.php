<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to login page where Firebase Google sign-in is handled.
     */
    public function redirectToGoogle()
    {
        return redirect()->route('login');
    }

    /**
     * Show mock Google consent screen for local testing (redirected to login).
     */
    public function mockConsent()
    {
        return redirect()->route('login');
    }

    /**
     * Process simulated Google login for development/testing.
     */
    public function processMock(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|max:100',
            'google_id' => 'nullable|string',
            'avatar'    => 'nullable|string',
        ]);

        $googleId = $validated['google_id'] ?? ('google_mock_' . substr(md5($validated['email']), 0, 16));
        $avatar   = $validated['avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($validated['name']) . '&background=B45309&color=fff');

        $user = User::where('google_id', $googleId)
            ->orWhere('email', $validated['email'])
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $user->google_id ?: $googleId,
                'avatar'    => $user->avatar ?: $avatar,
            ]);
        } else {
            $user = User::create([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'google_id'         => $googleId,
                'avatar'            => $avatar,
                'role'              => 'customer',
                'email_verified_at' => now(),
                'password'          => null,
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('customer.dashboard'))
            ->with('success', 'Successfully signed in with Google! Welcome back, ' . $user->name . '.');
    }

    /**
     * Handle Firebase Google authentication callback via AJAX.
     */
    public function handleFirebaseLogin(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email|max:100',
            'name'     => 'nullable|string|max:100',
            'uid'      => 'required|string|max:128',
            'avatar'   => 'nullable|string',
            'id_token' => 'nullable|string',
        ]);

        $name = trim($validated['name'] ?? '') ?: explode('@', $validated['email'])[0];
        $uid = $validated['uid'];
        $avatar = $validated['avatar'] ?: ('https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=B45309&color=fff');

        // Look up existing user by Google ID or email
        $user = User::where('google_id', $uid)
            ->orWhere('email', $validated['email'])
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $user->google_id ?: $uid,
                'avatar'    => $user->avatar ?: $avatar,
                'email_verified_at' => $user->email_verified_at ?: now(),
            ]);
        } else {
            $user = User::create([
                'name'              => $name,
                'email'             => $validated['email'],
                'google_id'         => $uid,
                'avatar'            => $avatar,
                'role'              => 'customer',
                'email_verified_at' => now(),
                'password'          => null,
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        $redirectUrl = $user->isAdmin()
            ? route('admin.dashboard')
            : redirect()->intended(route('customer.dashboard'))->getTargetUrl();

        return response()->json([
            'success'      => true,
            'redirect_url' => $redirectUrl,
            'message'      => 'Successfully signed in with Google! Welcome back, ' . $user->name . '.',
        ]);
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        // Check for cancellation or error returned by Google
        if ($request->has('error') || $request->has('denied')) {
            return redirect()->route('login')->with('warning', 'Google sign-in was cancelled.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();

            if (! $googleUser || ! $googleUser->getEmail()) {
                return redirect()->route('login')->with('error', 'Failed to retrieve your account details from Google.');
            }

            // Check if user already exists with this google_id or email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Link Google ID and update avatar if not already present
                $user->update([
                    'google_id' => $user->google_id ?: $googleUser->getId(),
                    'avatar'    => $user->avatar ?: $googleUser->getAvatar(),
                ]);
            } else {
                // Create a new customer user
                $user = User::create([
                    'name'              => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'Google User'),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'role'              => 'customer',
                    'email_verified_at' => now(),
                    'password'          => null,
                ]);
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'Successfully signed in with Google! Welcome back, ' . $user->name . '.');

        } catch (Exception $e) {
            Log::error('Google OAuth Callback Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }
}

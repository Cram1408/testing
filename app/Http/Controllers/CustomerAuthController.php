<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('customer.dashboard');
        }
        return view('auth.customer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (Auth::user()->is_deactivated) {
                Auth::logout();
                return back()->withInput($request->only('email'))->withErrors([
                    'email' => 'Your account has been deactivated. Please contact customer support.',
                ]);
            }

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('customer.dashboard');
        }
        return view('auth.customer-register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'        => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'email'       => 'required|email|unique:users,email|max:100',
            'password'    => 'required|string|min:6|confirmed',
            'phone'       => [
                'required',
                'digits:10',
                'regex:/^[6-9]\d{9}$/',
            ],
            'address'     => 'nullable|string|max:255',
            'city'        => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'state'       => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'postal_code' => [
                'nullable',
                'digits:6',
                'regex:/^\d{6}$/',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ], [
            'name.required' => 'Full name is required.',
            'name.regex' => 'Full name may only contain alphabetic characters and spaces. Numbers and special characters are not allowed.',
            'phone.required' => 'Mobile number is required.',
            'phone.digits' => 'Mobile number must contain exactly 10 digits.',
            'phone.regex' => 'Enter a valid 10-digit Indian mobile number (starting with 6–9) without alphabets or special characters.',
            'city.regex' => 'City may only contain alphabetic characters and spaces. Numbers and special characters are not allowed.',
            'state.regex' => 'State may only contain alphabetic characters and spaces. Numbers and special characters are not allowed.',
            'postal_code.digits' => 'PIN code must contain exactly 6 digits.',
            'postal_code.regex' => 'PIN code must contain exactly 6 digits without any alphabets or special characters.',
            'avatar.image' => 'The profile picture must be an image file.',
            'avatar.mimes' => 'The profile picture must be a JPG, PNG, or WEBP image.',
            'avatar.max' => 'The profile picture size must not exceed 2MB.',
        ]);

        // Ensure the mobile number was OTP-verified in this session
        $sessionKey = "otp_verified_registration_{$validated['phone']}";
        if (! $request->session()->get($sessionKey)) {
            return back()->withInput()->withErrors([
                'phone' => 'Please verify your mobile number with OTP before creating an account.',
            ]);
        }

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'role'        => 'customer',
            'phone'       => $validated['phone'],
            'avatar'      => $avatarPath,
            'address'     => $validated['address'] ?? null,
            'city'        => $validated['city'] ?? null,
            'state'       => $validated['state'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
        ]);

        // Consume the session flag so it cannot be replayed
        $request->session()->forget($sessionKey);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')
            ->with('success', 'Your account has been created successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}

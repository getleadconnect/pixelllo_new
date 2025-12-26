<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:2',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048', // 2MB max
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'city' => $request->city,
            'country' => $request->country ?? 'US',
            'role' => 'customer',
            'bid_balance' => 10, // Start with 10 free bids
            'notification_preferences' => json_encode([
                'outbid_notification' => true,
                'ending_notification' => true,
                'new_notification' => true,
                'order_notification' => true,
                'promo_notification' => true,
            ]),
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            try {
                $image = $request->file('profile_image');
                $imageName = 'profile_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('profiles', $imageName, 'public');
                $userData['avatar'] = $imagePath;

                // Log successful upload for debugging
                \Log::info('Profile image uploaded successfully', [
                    'user_email' => $request->email,
                    'image_path' => $imagePath
                ]);
            } catch (\Exception $e) {
                // Log error but continue registration without image
                \Log::error('Profile image upload failed', [
                    'error' => $e->getMessage(),
                    'user_email' => $request->email
                ]);
            }
        }

        $user = User::create($userData);

        // Log the user in after registration
        Auth::login($user);

        // For API requests, return JSON with token
        if ($request->wantsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        }

        // For web requests, redirect to dashboard
        return redirect(url('/dashboard'));
    }

    /**
     * Login a user and return token or redirect for web
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            if ($request->wantsJson()) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            return back()->withErrors([
                'email' => ['The provided credentials are incorrect.'],
            ])->withInput($request->except('password'));
        }

        $user = User::where('email', $request->email)->firstOrFail();

        if (!$user->active) {
            if ($request->wantsJson()) {
                throw ValidationException::withMessages([
                    'email' => ['This account has been deactivated.'],
                ]);
            }

            return back()->withErrors([
                'email' => ['This account has been deactivated.'],
            ])->withInput($request->except('password'));
        }

        // For API requests, return JSON with token
        if ($request->wantsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        }

        // For web requests, redirect to dashboard
        return redirect()->intended(url('/dashboard'));
    }

    /**
     * Get authenticated user information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Logout user (revoke token)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // For API tokens
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        // For web session
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // For API requests
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }

        // For web requests
        return redirect(url('/'));
    }

    /**
     * Update user password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match our records.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }
}

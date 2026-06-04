<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider)
    {
        try {
            // Using stateless() to avoid 403 InvalidStateException caused by session/cookie mismatches
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Find or create the user
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Update provider info if not already set
                if (!$user->provider_id) {
                    $user->update([
                        'provider_name' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'provider_token' => $socialUser->token,
                    ]);
                }
            } else {
                // Create a new user
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'slug_name' => Str::slug($socialUser->getName() ?? $socialUser->getNickname() ?? 'User') . '-' . Str::random(5),
                    'email' => $socialUser->getEmail(),
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                    'password' => null, // Social users don't need a password initially
                    'user_type' => 'customer',
                    'email_verified_at' => now(), // Social providers usually verify emails
                ]);

                event(new Registered($user));
            }

            Auth::guard('web')->login($user);

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            \Log::error('Social Login Error: ' . $e->getMessage(), [
                'provider' => $provider,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')->with('error', 'Something went wrong with social login: ' . $e->getMessage());
        }
    }
}

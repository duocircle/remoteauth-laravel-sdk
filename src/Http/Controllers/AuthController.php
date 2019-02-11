<?php

namespace RemoteAuth\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use RemoteAuth\RemoteAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Two\User;

class AuthController
{
    /**
     * Redirects to RemoteAuth to start the authentication flow.
     *
     * @return Redirect
     */
    public function login()
    {
        return Socialite::driver('remoteauth')
            ->scopes(config('services.remoteauth.scopes'))
            ->redirect();
    }

    /**
     * Handle the callback from RemoteAuth during the authentication flow.
     *
     * @param Request $request
     */
    public function callback(Request $request)
    {
        $userDetails = Socialite::driver('remoteauth')->user();

        $handler = RemoteAuth::handler() ?: function (User $userDetails) {
            $userModel = RemoteAuth::userModel();

            if (!$userModel) {
                throw new \Exception('Missing $userModel on RemoteAuth class');
            }

            $user = $userModel::firstOrNew([
                'email' => $userDetails->email,
            ], [
                'name' => $userDetails->name
            ]);
            
            $user->handleTokenRefresh($userDetails->id, $userDetails->token, $userDetails->refreshToken, $userDetails->expiresIn);

            Auth::login($user);

            return redirect('/');
        };

        return call_user_func($handler, $userDetails);
    }
}

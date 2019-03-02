<?php

namespace RemoteAuth;

use Illuminate\Support\Facades\Route;

class RemoteAuth
{
    /** @var \Closure */
    private static $handler;

    /** @var string */
    private static $userModel;

    /**
     * Registers the authentication routes for RemoteAuth.
     *
     * @return void
     */
    public static function registerRoutes(?\Closure $handler = null)
    {
        RemoteAuth::$handler = $handler;

        Route::group([
            'middleware' => ['web'],
            'namespace' => '\RemoteAuth\Http\Controllers'
        ], function ($router) {
            $router->get('/login/remoteauth', [
                'uses' => 'AuthController@login',
                'as' => 'remoteauth.auth.login',
            ]);

            $router->get('/login/remoteauth/callback', [
                'uses' => 'AuthController@callback',
                'as' => 'remoteauth.auth.callback',
            ]);
        });
    }

    /**
     * Sets the model to use for users.
     *
     * @param string $userModel
     * @return void
     */
    public static function setUserModel(string $userModel)
    {
        static::$userModel = $userModel;
    }

    /**
     * Returns the model to use for users.
     *
     * @return string
     */
    public static function userModel()
    {
        return static::$userModel;
    }

    /**
     * Returns the handler used during oauth flow.
     *
     * @return \Closure
     */
    public static function handler()
    {
        return static::$handler;
    }
}

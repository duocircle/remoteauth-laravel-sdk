# RemoteAuth Laravel SDK

You can use this package to quickly get up and running with [RemoteAuth](https://remoteauth.com).

The RemoteAuth Laravel SDK uses the RemoteAuth PHP SDK behind the scenes.

## Setup

### Configuration

You need to add the following configuration values to `config/services.php`:

```
    'remoteauth' => [
        'client_id' => env('REMOTEAUTH_CLIENT_ID'),
        'client_secret' => env('REMOTEAUTH_CLIENT_SECRET'),
        'url' => env('REMOTEAUTH_URL'),
        'redirect' => config('app.url') . '/login/remoteauth/callback',
        'scopes' => ''
    ],
```

* `client_id` - Get this from the RemoteAuth OAuth Clients UI.
* `client_secret` - Get this from the RemoteAuth OAuth Clients UI.
* `url` - This is the URL of the RemoteAuth server you are using.
* `redirect` - This is the endpoint of your application that RemoteAuth will respond to during the OAuth workflow.
* `scopes` - The scopes that should be requested when granting an access token.

### User

There's a configuration variable available for declaring which of your models represents a RemoteAuth User.

```php
RemoteAuth::setUserModel(\App\User::class);
```

Your `User` model must implement the [`RemoteAuthUser`](https://github.com/owenconti/remoteauth-php-sdk/blob/master/src/RemoteAuthUser.php) interface provided by `remoteauth-php-sdk`.

Here's a standard example of the overridden methods:

```php
class User extends Authenticatable implements RemoteAuthUser
{

    // ... standard user model stuff here
    
    /** @Override */
    public function remoteAuthUserId(): string
    {
        return $this->remoteauth_user_id;
    }
    
    /** @Override */
    public function accessToken(): string
    {
        return $this->access_token;
    }

    /** @Override */
    public function refreshToken(): string
    {
        return $this->refresh_token;
    }

    /** @Override */
    public function accessTokenExpiration(): \DateTime
    {
        return $this->expires_at;
    }

    public function handleTokenRefresh(string $userId, string $accessToken, string $refreshToken, int $expiresIn): void
    {
        $this->remoteauth_user_id = $userId;
        $this->access_token = $accessToken;
        $this->refresh_token = $refreshToken;
        $this->expires_at = Carbon::now()->addSeconds($expiresIn);
        $this->save();
    }
}
```

### Service Provider Registration

The RemoteAuth Laravel SDK comes with a standard setup to quickly get you up and running. It will handle the entire OAuth workflow for you, granted you have a standard workflow.

Inside `AppServiceProvider.php`, register your `User` model and register the routes used for the OAuth flow:

```php
// AppServiceProvider.php

/**
 * Bootstrap any application services.
 *
 * @return void
 */
public function boot()
{
    RemoteAuth::setUserModel(\App\User::class);
    RemoteAuth::registerRoutes();
}
```

The `RemoteAuth::registerRoutes()` method can optionally accept a closure argument. If passed, this closure will be called when the OAuth workflow is successful.

The closure is passed an arugment, `$userDetails`. This argument is the user object returned from Socialite:

```php
RemoteAuth::registerRoutes(function(User $userDetails) {
    $user = \App\User::firstOrNew([
        'email' => $userDetails->email,
    ], [
        'name' => $userDetails->name,
    ]);

    $user->handleTokenRefresh($userDetails->id, $userDetails->token, $userDetails->refreshToken, $userDetails->expiresIn);

    Auth::login($user);

    return redirect('/');
});
```

If you do not pass the closure, the default closure will update (or create if new) the User identified by their email address. It will then call the `handleTokenRefresh()` method on your `User` class, and then login the user in.

`$userDetails` passed into the closure contains the following properties:

* id
* name
* email
* token
* refreshToken
* expiresIn

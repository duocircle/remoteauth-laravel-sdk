# RemoteAuth Laravel SDK

You can use this package to quickly get up and running with [RemoteAuth](https://remoteauth.com).

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

### Routes

You need to setup two routes for use with the OAuth workflow.

First route is to redirect your application to RemoteAuth, using Socialite:

```
GET: /login/remoteauth

public function login()
{
    return Socialite::driver('remoteauth')->redirect();
}
```

The second route is to handle the callback from RemoteAuth. This route's endpoint needs to match the `redirect` configuration value set above.

```
GET: /login/remoteauth/callback

public function callback(Request $request)
{
    $userDetails = Socialite::driver('remoteauth')->user();

    // Store user details in your database
    // Login the user in
    // Continue with your auth flow
}
```

`$userDetails` contains information about the authenticated user:

* id
* name
* email
* token
* refreshToken
* expiresIn

You should store this information in your `users` table.


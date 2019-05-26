<?php

namespace RemoteAuth;

use Illuminate\Contracts\Container\Container;
use RemoteAuthPhp\Client;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\CacheInterface;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class RemoteAuthServiceProvider extends EventServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('RemoteAuthPhp\Client', function (Container $app) {
            $config = $app['config'];

            return new Client([
                'baseUrl'      => $config['services']['remoteauth']['url'],
                'clientId'     => $config['services']['remoteauth']['client_id'],
                'clientSecret' => $config['services']['remoteauth']['client_secret'],
                'scope'        => $config['services']['remoteauth']['scopes'],
            ], Cache::getFacadeRoot()->store());
        });
    }

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            '\SocialiteProviders\RemoteAuth\RemoteAuthExtendSocialite@handle'
        ]
    ];
}

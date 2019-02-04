<?php

namespace RemoteAuth;

use Illuminate\Support\ServiceProvider;

class RemoteAuthServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        RemoteAuthSDK::class => RemoteAuthSDK::class,
    ];
}

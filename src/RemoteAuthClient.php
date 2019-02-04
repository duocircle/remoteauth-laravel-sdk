<?php

namespace RemoteAuth;

class RemoteAuthClient
{
    private static $apiVersion = 'v1';

    /** @var HttpClient */
    private $http;

    /**
     * Prepares the SDK for to make requests for the given User.
     *
     * @param RemoteAuthUser $user
     * @return RemoteAuthSDK
     */
    public function forUser(RemoteAuthUser $user)
    {
        $this->http = new HttpClient($user);
        
        return $this;
    }

    public function applicationMembers()
    {
        return $this->http->get($this->url('users/applicationMembers/byToken'));
    }

    private function url(string $url)
    {
        return '/api/' . RemoteAuthClient::$apiVersion . '/' . $url;
    }
}

<?php

namespace RemoteAuth;

use GuzzleHttp\Client;

class HttpClient
{
    /** @var string */
    private $accessToken;

    /** @var string */
    private $refreshToken;

    /** @var Carbon */
    private $accessTokenExpiration;

    /** @var string */
    private $remoteAuthUserId;

    /** @var Client */
    private $client;

    public function __construct(RemoteAuthUser $user)
    {
        $this->accessToken = $user->getAccessToken();
        $this->refreshToken = $user->getRefreshToken();
        $this->accessTokenExpiration = $user->getAccessTokenExpiration();
        $this->remoteAuthUserId = $user->getRemoteAuthUserId();

        $this->client = new Client();
    }

    public function get(string $url)
    {
        return $this->request('GET', $url);
    }

    private function request(string $method, string $url, ?array $payload = [])
    {
        $options = [
            'headers' => [
                'Authentication' => 'Bearer ' . $this->accessToken
            ]
        ];

        if (!empty($payload)) {
            $options['json'] = $payload;
        }

        $response = $this->client->request($method, $url, $options);
        dd($response->getStatusCode());
    }
}

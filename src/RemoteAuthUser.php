<?php

namespace RemoteAuth;

use Carbon\Carbon;

interface RemoteAuthUser
{
    /**
     * Returns the User's RemoteAuth ID.
     *
     * @return string
     */
    public function getRemoteAuthUserId(): string;

    /**
     * Returns the User's access token.
     *
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * Returns the User's refresh token.
     *
     * @return string
     */
    public function getRefreshToken(): string;

    /**
     * Returns the date the access token expires.
     *
     * @return Carbon
     */
    public function getAccessTokenExpiration(): Carbon;
}

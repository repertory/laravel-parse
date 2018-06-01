<?php

namespace LaravelParse;

use Parse\ParseUser;
use Illuminate\Contracts\Auth\Authenticatable;

class User extends ParseUser implements Authenticatable
{

    /**
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'objectId';
    }

    /**
     * @return mixed|null|string
     */
    public function getAuthIdentifier()
    {
        return $this->objectId;
    }

    /**
     * @return null|string
     */
    public function getAuthPassword()
    {
        return null;
    }

    /**
     * @return mixed|string
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * @param string $value
     * @return string|void
     */
    public function setRememberToken($value)
    {
        return $this->rememberToken = $value;
    }

    /**
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'rememberToken';
    }
}

<?php

namespace LaravelParse;

use Parse\ParseException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as Provider;

class ParseProvider implements Provider
{

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param mixed $identifier
     * @return array|Authenticatable|null|\Parse\ParseObject
     * @throws ParseException
     */
    public function retrieveById($identifier)
    {
        $query = $this->user->query();
        return $query->get($identifier, true);
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return array|Authenticatable|null|\Parse\ParseObject
     * @throws \Exception
     */
    public function retrieveByToken($identifier, $token)
    {
        $query = $this->user->query();
        $query->equalTo('objectId', $identifiere);
        $query->equalTo('rememberToken', $token);
        return $query->first(true);
    }

    /**
     * @param ParseUser $user
     * @param string $token
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->rememberToken = $token;
        return $user->save(true);
    }

    /**
     * @param array $credentials
     * @return Authenticatable|null|ParseUser
     * @throws ParseException
     */
    public function retrieveByCredentials(array $credentials)
    {
        $username = $this->getUsernameFromCredentials($credentials);
        return $this->user->logIn($this->getUsernameFromCredentials($credentials), $credentials['password']);
    }

    /**
     * @param ParseUser $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        try {
            $this->user->logIn($this->getUsernameFromCredentials($credentials), $credentials['password']);
            return true;
        } catch (ParseException $error) {
            return false;
        }
    }

    /**
     * @param array $credentials
     * @return mixed
     * @throws ParseException
     */
    private function getUsernameFromCredentials(array $credentials)
    {
        if (array_key_exists('username', $credentials)) {
            return $credentials['username'];
        } elseif (array_key_exists('email', $credentials)) {
            return $credentials['email'];
        } else {
            throw new ParseException('$credentials must contain either a "username" or "email" key');
        }
    }
}

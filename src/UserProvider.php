<?php

namespace LaravelParse;

use Parse\ParseException;
use Parse\ParseUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as Provider;

class UserProvider implements Provider
{

    /**
     * @param mixed $identifier
     * @return array|Authenticatable|null|\Parse\ParseObject
     * @throws ParseException
     */
    public function retrieveById($identifier)
    {
        $query = ParseUser::query();
        return $query->get($identifier, true);
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $query = ParseUser::query();
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
     * @return array|Authenticatable|null|\Parse\ParseObject
     * @throws ParseException
     */
    public function retrieveByCredentials(array $credentials)
    {
        $username = $this->getUsernameFromCredentials($credentials);

        $query = ParseUser::query();
        $query->equalTo('username', $username);
        $user = $query->first(true);

        return empty($user) ? null : $user;
    }

    /**
     * @param ParseUser $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        try {
            ParseUser::logIn($this->getUsernameFromCredentials($credentials), $credentials['password']);
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

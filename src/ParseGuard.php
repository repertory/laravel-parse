<?php

namespace LaravelParse;

use Parse\ParseUser;
use Parse\ParseClient;
use Parse\ParseException;
use Parse\ParseMemoryStorage;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Authenticatable;

class ParseGuard implements Guard, StatefulGuard
{

    /**
     * @return bool
     */
    public function check()
    {
        return !!$this->user();
    }

    /**
     * @return bool
     */
    public function guest()
    {
        return !$this->user();
    }

    /**
     * @return Authenticatable|null|ParseUser
     */
    public function user()
    {
        return ParseUser::getCurrentUser();
    }

    /**
     * @return int|null|string
     */
    public function id()
    {
        return $this->user()->getObjectId();
    }

    /**
     * @param array $credentials
     * @return bool
     * @throws \Parse\ParseException
     */
    public function validate(array $credentials = [])
    {
        try {
            $user = ParseUser::logIn($this->getUsernameFromCredentials($credentials), $credentials['password']);
            return true;
        } catch (ParseException $error) {
            return false;
        }
    }

    /**
     * @param Authenticatable $user
     * @return ParseUser|void
     */
    public function setUser(Authenticatable $user)
    {
        return ParseUser::become($user->getSessionToken());
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array $credentials
     * @param  bool $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        try {
            ParseUser::logIn($this->getUsernameFromCredentials($credentials), $credentials['password']);
            return true;
        } catch (ParseException $error) {
            return false;
        }
    }

    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  array $credentials
     * @return bool
     */
    public function once(array $credentials = [])
    {
        ParseClient::setStorage(new ParseMemoryStorage());
        try {
            ParseUser::logIn($this->getUsernameFromCredentials($credentials), $credentials['password']);
            return true;
        } catch (ParseException $error) {
            return false;
        }
    }

    /**
     * Log a user into the application.
     *
     * @param  Authenticatable $user
     * @param  bool $remember
     * @return void
     */
    public function login(Authenticatable $user, $remember = false)
    {
        return ParseUser::become($user->getCurrentUser()->getSessionToken());
    }

    /**
     * Log the given user ID into the application.
     *
     * @param  mixed $id
     * @param  bool $remember
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function loginUsingId($id, $remember = false)
    {
        $query = ParseUser::query();
        return $this->login($query->get($id));
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     *
     * @param  mixed $id
     * @return bool
     */
    public function onceUsingId($id)
    {
        ParseClient::setStorage(new ParseMemoryStorage());
        $query = ParseUser::query();
        return $this->login($query->get($id));
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember()
    {
        return false;
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        return ParseUser::logOut();
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

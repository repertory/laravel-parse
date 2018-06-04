<?php

namespace LaravelParse;

use Parse\ParseClient;
use Parse\ParseException;
use Parse\ParseMemoryStorage;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Authenticatable;

class ParseGuard implements Guard, StatefulGuard
{

    protected $viaRemember = false;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function check()
    {
        return $this->user->isCurrent();
    }

    /**
     * @return bool
     */
    public function guest()
    {
        return !$this->user->isAuthenticated();
    }

    /**
     * @return Authenticatable|null|\Parse\ParseUser
     */
    public function user()
    {
        return $this->user->getCurrentUser();
    }

    /**
     * @return int|null|string
     */
    public function id()
    {
        return $this->user->getObjectId();
    }

    /**
     * @param array $credentials
     * @return bool
     * @throws \Parse\ParseException
     */
    public function validate(array $credentials = [])
    {
        return $this->attempt($credentials);
    }

    /**
     * @param Authenticatable $user
     * @return \Parse\ParseUser|void
     */
    public function setUser(Authenticatable $user)
    {
        return $this->user->become($user->getSessionToken());
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
            $this->user->logIn($this->getUsernameFromCredentials($credentials), $credentials['password']);
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
        return $this->attempt($credentials);
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
        // TODO 目前仅支持账号密码登陆
        return $this->user;
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
        $query = User::query();
        return $this->login($query->get($id, true));
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
        return $this->loginUsingId($id, true);
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember()
    {
        return $this->viaRemember;
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        return $this->user->logOut();
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

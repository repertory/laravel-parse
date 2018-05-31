<?php

namespace LaravelParse;

use Parse\ParseStorageInterface;
use Illuminate\Session\SessionManager;

class LaravelSessionStorage implements ParseStorageInterface
{

    private $session;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function set($key, $value)
    {
        $this->session->put($key, $value);
    }

    public function remove($key)
    {
        $this->session->forget($key);
    }

    public function get($key)
    {
        if ($this->session->has($key)) {
            return $this->session->get($key);
        }
    }

    public function clear()
    {
        $this->session->forget();
    }

    public function save()
    {
        //
    }

    public function getKeys()
    {
        return array_keys($this->session->get());
    }

    public function getAll()
    {
        return $this->session->get();
    }

}

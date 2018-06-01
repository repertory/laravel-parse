<?php

namespace LaravelParse;

use Parse\ParseStorageInterface;
use Illuminate\Support\Facades\Session;

class LaravelSessionStorage implements ParseStorageInterface
{

    public function set($key, $value)
    {
        return Session::put($key, $value);
    }

    public function remove($key)
    {
        return Session::remove($key);
    }

    public function get($key)
    {
        return Session::get($key);
    }

    public function clear()
    {
        return Session::flush();
    }

    public function save()
    {
        return Session::save();
    }

    public function getKeys()
    {
        return array_keys(Session::all());
    }

    public function getAll()
    {
        return Session::all();
    }

}

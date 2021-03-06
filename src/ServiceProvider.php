<?php

namespace LaravelParse;

use Parse\ParseClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{

    public function boot()
    {
        $path = dirname(__DIR__);  // 根路径

        if ($this->app->runningInConsole()) {
            // 复制文件
            $this->publishes([
                $path . '/config/parse.php' => base_path('config/parse.php'),
            ]);
        }

        // 初始化Parse
        if (config('parse.server_url')) {
            ParseClient::initialize(config('parse.app_id', ''), config('parse.rest_key', ''), config('parse.master_key', ''));
            ParseClient::setServerURL(config('parse.server_url', ''), config('parse.mount_path', ''));

            if ($this->app->has('session')) {
                ParseClient::setStorage(new LaravelSessionStorage());
            }
        }

        if (config('parse.auth')) {
            User::registerSubclass();
            $authenticatable = new User();
            Auth::extend('parse', function () use ($authenticatable) {
                return new ParseGuard($authenticatable);
            });
            Auth::provider('parse', function () use ($authenticatable) {
                return new ParseProvider($authenticatable);
            });
        }
    }

    public function register()
    {
        $path = dirname(__DIR__);  // 根路径
        $this->mergeConfigFrom($path . '/config/parse.php', 'parse');
    }

}

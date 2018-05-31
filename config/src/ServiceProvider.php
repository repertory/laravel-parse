<?php

namespace LaravelModule;

use Parse\ParseClient;
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
        ParseClient::initialize(config('parse.app_id', ''), config('parse.rest_key', ''), config('parse.master_key', ''));
        ParseClient::setServerURL(config('parse.server_url', ''), config('parse.mount_path', ''));
    }

    public function register()
    {
        $path = dirname(__DIR__);  // 根路径
        $this->mergeConfigFrom($path . '/config/parse.php', 'parse');
    }

}

# laravel-parse

[![Latest Stable Version](https://poser.pugx.org/wangdong/laravel-parse/version)](https://packagist.org/packages/wangdong/laravel-parse)
[![Total Downloads](https://poser.pugx.org/wangdong/laravel-parse/downloads)](https://packagist.org/packages/wangdong/laravel-parse)
[![Latest Unstable Version](https://poser.pugx.org/wangdong/laravel-parse/v/unstable)](//packagist.org/packages/wangdong/laravel-parse)
[![License](https://poser.pugx.org/wangdong/laravel-parse/license)](https://packagist.org/packages/wangdong/laravel-parse)
[![composer.lock available](https://poser.pugx.org/wangdong/laravel-parse/composerlock)](https://packagist.org/packages/wangdong/laravel-parse)

## 安装
```
composer require wangdong/laravel-parse
```

## 初始化

**Laravel**
```
# laravel 5.1 - 5.4需文件`config/app.php`增加providers项
LaravelParse\ServiceProvider::class,

# 生成配置文件
php artisan vendor:publish --provider=LaravelParse\\ServiceProvider
```

**Lumen**
```
# 文件`bootstrap/app.php`增加providers项
$app->register(LaravelParse\ServiceProvider::class);
```

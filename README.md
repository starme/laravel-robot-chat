# laravel-robot-chat

## Installation

```sh
composer require starme/laravel-robot-chat
php artisan vendor:publish --provider=RobotServiceProvider
```

## Config

redis throttle key
```php
'rate_cache_key' => 'robot',
```

Up to 20 messages per minute. default.
```php
'rate_allow' => [20, 60],
```
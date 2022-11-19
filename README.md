# 友盟推送

[![Latest Version on Packagist](https://img.shields.io/packagist/v/chuoke/umeng-push.svg?style=flat-square)](https://packagist.org/packages/chuoke/umeng-push)
[![Tests](https://github.com/chuoke/umeng-push/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/chuoke/umeng-push/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/chuoke/umeng-push.svg?style=flat-square)](https://packagist.org/packages/chuoke/umeng-push)

## Installation

使用 composer 安装:

```bash
composer require chuoke/umeng-push
```

## 使用

```php
<?php

use Chuoke\UmengPush\Config;
use Chuoke\UmengPush\UmengPusher;
use Chuoke\UmengPush\Messages\AndroidMessage;
use Chuoke\UmengPush\Payloads\AndroidPayload;
use Chuoke\UmengPush\Policies\AndroidPolicy;

$androidMessage = AndroidMessage::make()
    ->payload(
        AndroidPayload::make()
            ->title('这是标题')
            ->text('这是文本')
            ->extra([
                'path' => 'home',
            ])
    )
    ->description('一条消息')
    ->broadcast();

// 使用设备号
$androidMessage->deviceTokens(['t1', 't2']);
// or 使用别名
$androidMessage->alias(['t1', 't2'], 'user_type');

$startTime = '2022-12-23 23:45:23';
if (strtotime($startTime) > time()) {
    $androidMessage->policy(AndroidPolicy::make()->startTime($startTime)->outBizNo('123'));
}

$config = Config::make([
    'production_mode' => true,
    'app_key' => 'app_push_key',
    'app_secret' => 'app_push_secret',
]);

$res = UmengPusher::make($config)->send($androidMessage);

if ($res->isOk()) {
    // to something;
}
```

### 在 Laravel 中使用

```php
<?php

// 在 AppServiceProvider 中注册
use \Chuoke\UmengPush\UmengPushChannel;

$config = [
    'android' => [
        'production_mode' => true,
        'app_key' => 'app_push_key',
        'app_secret' => 'app_push_secret',
    ],
    'ios' => [
        'production_mode' => true,
        'app_key' => 'app_push_key',
        'app_secret' => 'app_push_secret',
    ],
];

$this->app->bind(UmengPushChannel::class, function ($app) {
        return new UmengPushChannel($config);
    });

// 或者
$this->app->when(UmengPushChannel::class)
          ->needs('$config')
          ->give($config);
```

在通知类中添加响应内容

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use \Chuoke\UmengPush\UmengPushChannel;

class SomeNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [UmengPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return mixed
     */
    public function toUmengPush($notifiable)
    {
        $messages = [];
        if ($device = $notifiable->androidDevice()) {
            $messages[] = AndroidMessage::make()
                ->payload(
                    AndroidPayload::make()
                        ->title('这是标题')
                        ->text('这是文本')
                        ->extra([
                            'path' => 'home',
                        ])
                )
                ->listcast();
        }

        // 或者使用别名
        $messages[] = AndroidMessage::make()
            ->payload(
                AndroidPayload::make()
                    ->title('这是标题')
                    ->text('这是文本')
                    ->extra([
                        'path' => 'home',
                    ])
            )
            ->alias($notifiable->getKey(), 'user_id')
            ->listcast();

        $messages[] = IosMessage::make()
            ->payload(
                IosPayload::make()
                    ->alert('这是标题', '这是文本')
                    ->customParams([
                        'path' => 'home',
                    ])
            )
            ->alias($notifiable->getKey(), 'user_id')
            ->listcast();

        return $messages;
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [chuoke](https://github.com/)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

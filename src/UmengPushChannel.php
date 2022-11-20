<?php

namespace Chuoke\UmengPush;

use Chuoke\UmengPush\Contracts\Message;
use Chuoke\UmengPush\Messages\AndroidMessage;
use Chuoke\UmengPush\Messages\IosMessage;
use Illuminate\Notifications\Notification;

class UmengPushChannel
{
    /** @var array 配置信息 */
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toUmengPush($notifiable);

        $messages = is_array($message) ? $message : [$message];

        foreach ($messages as $message) {
            if ($message instanceof Message) {
                continue;
            }

            if ($message instanceof AndroidMessage && array_key_exists('android', $this->config)) {
                UmengPusher::make($this->config['android'])->send($message);
            } elseif ($message instanceof IosMessage && array_key_exists('ios', $this->config)) {
                UmengPusher::make($this->config['ios'])->send($message);
            }
        }
    }
}

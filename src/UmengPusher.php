<?php

namespace Chuoke\UmengPush;

use Chuoke\UmengPush\Contracts\Client as ClientInterface;
use Chuoke\UmengPush\Messages\Message;
use RuntimeException;

/**
 * 友盟推送
 *
 * @see https://developer.umeng.com/docs/67966/detail/68343
 *
 * @method Response|mixed send(Message $message)
 * @method Response|mixed status(string $taskId)
 * @method Response|mixed taskStat(string $taskId)
 * @method Response|mixed cancel(string $taskId)
 * @method Response|mixed upload(array|string $content)
 */
class UmengPusher
{
    /** @var Config */
    protected $config;

    /** @var ClientInterface 请求客户端 */
    protected $client;

    public function __construct(Config $config = null)
    {
        $this->config = $config;
    }

    /**
     * 实例化对象
     *
     * @param  Config  $config
     * @return static
     */
    public static function make(Config $config = null)
    {
        return new static($config);
    }

    /**
     * 设置配置信息
     *
     * @param  Config  $config
     * @return $this
     */
    public function config(Config $config)
    {
        $this->config = $config;

        $this->configToCilent();

        return $this;
    }

    /**
     * 设置请求客户端
     *
     * @param  ClientInterface  $client
     * @return $this
     */
    public function client(ClientInterface $client)
    {
        $this->client = $client;

        $this->configToCilent();

        return $this;
    }

    /**
     * 为客户端设置配置信息
     *
     * @param  Config|null  $config
     * @return $this
     */
    public function configToCilent($config = null)
    {
        if (! $this->client) {
            return $this;
        }

        if ($config) {
            $this->client->config($config);
        } elseif ($this->config) {
            $this->client->config($this->config);
        }

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (! $this->client) {
            $this->client = Client::make();
            $this->configToCilent();
        }

        if (method_exists($this->client, $name)) {
            return $this->client->{$name}(...$arguments);
        }

        throw new RuntimeException("方法 {$name} 不存在");
    }
}

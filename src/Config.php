<?php

namespace Chuoke\UmengPush;

use Chuoke\UmengPush\Exceptions\UmengPushException;

/**
 *@property string $app_key
 *@property string $app_secret
 *@property string $base_url
 *@property string $production_mode
 */
class Config
{
    /** @var string 应用唯一标识。 */
    protected $app_key = '';

    /** @var string 服务器秘钥 */
    protected $app_secret = '';

    /**
     *  @var string 接口基础地址 */
    protected $base_url = 'https://msgapi.umeng.com';

    /** @var string 正式/测试模式。默认为true */
    protected $production_mode = 'true';

    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * 实例化
     *
     * @param  array  $config
     * @return static
     */
    public static function make(array $config)
    {
        return new static($config);
    }

    /**
     * 设置 APP key
     *
     * @param string $appKey
     * @return $this
     */
    public function appKey(string $appKey)
    {
        $this->app_key = $appKey;

        return $this;
    }

    /**
     * 设置 APP secret
     *
     * @param string $appSecret
     * @return $this
     */
    public function appSecret(string $appSecret)
    {
        $this->app_secret = $appSecret;
        return $this;
    }

    /**
     * 设置接口基础地址
     *
     * @param string $baseUrl
     * @return $this
     */
    public function baseUrl(string $baseUrl)
    {
        $this->base_url = $baseUrl;
        return $this;
    }

    /**
     * 设置推送环境模式
     *
     * @param string $productionMode
     * @return $this
     */
    public function productionMode(string $productionMode)
    {
        $this->production_mode = $productionMode;
        return $this;
    }

    public function __get($name)
    {
        if ($name === 'production_mode') {
            return filter_var($this->production_mode, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
        }

        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        throw new UmengPushException("属性 {$name} 不存在");
    }
}

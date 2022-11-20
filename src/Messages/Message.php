<?php

namespace Chuoke\UmengPush\Messages;

use Chuoke\UmengPush\Contracts\Message as MessageInterface;
use Chuoke\UmengPush\Exceptions\UmengPushException;

/**
 * 消息内容
 *
 * @see https://developer.umeng.com/docs/67966/detail/68343#h1-u6D88u606Fu53D1u90014
 */
class Message implements MessageInterface
{
    /** @var string 单播 */
    const TYPE_UNI_CAST = 'unicast';

    /** @var string 列播 */
    const TYPE_LIST_CAST = 'listcast';

    /** @var string 文件播 */
    const TYPE_FILE_CAST = 'filecast';

    /** @var string 广播 */
    const TYPE_BROAD_CAST = 'broadcast';

    /** @var string 组播 */
    const TYPE_GROUP_CAST = 'groupcast';

    /** @var string 通过alias进行推送 */
    const TYPE_CUSTOMIZE_CAST = 'customizedcast';

    /** @var string 消息发送类型 */
    protected $type;

    /** @var mixed 设备号 */
    protected $device_tokens;

    /** @var string alias的类型 */
    protected $alias_type;

    /** @var mixed 要求不超过500个alias */
    protected $alias;

    /** @var string 文件ID */
    protected $file_id;

    /** @var array 用户筛选条件 */
    protected $filter;

    /** @var Payload|null 消息内容 */
    protected $payload;

    /** @var Policy|null 发送策略 */
    protected $policy;

    /** @var string 发送消息描述，建议填写接口 */
    protected $description;

    /**
     * 实例化
     *
     * @return static
     */
    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        //
    }

    /**
     * 设置消息发送类型
     *
     * @param  string  $type
     * @return $this
     */
    protected function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * 单播
     *
     * @return $this
     */
    public function unicast()
    {
        return $this->type(self::TYPE_UNI_CAST);
    }

    /**
     * 列播
     *
     * @return $this
     */
    public function listcast()
    {
        return $this->type(self::TYPE_LIST_CAST);
    }

    /**
     * 文件播
     *
     * @return $this
     */
    public function filecast()
    {
        return $this->type(self::TYPE_FILE_CAST);
    }

    /**
     * 广播
     *
     * @return $this
     */
    public function broadcast()
    {
        return $this->type(self::TYPE_BROAD_CAST);
    }

    /**
     * 组播
     *
     * @return $this
     */
    public function groupcast()
    {
        return $this->type(self::TYPE_UNI_CAST);
    }

    /**
     * 通过alias进行推送
     *
     * @return $this
     */
    public function customizedcast()
    {
        return $this->type(self::TYPE_CUSTOMIZE_CAST);
    }

    /**
     * 设置设备号
     *
     * @param  mixed  $device_tokens
     * @return $this
     */
    public function deviceTokens($device_tokens)
    {
        $this->device_tokens = $device_tokens;

        return $this;
    }

    /**
     * 设置别名类型
     *
     * @param  mixed  $alias_type
     * @return $this
     */
    public function aliasType($alias_type)
    {
        $this->alias_type = $alias_type;

        return $this;
    }

    /**
     * 设置别名
     *
     * @param  mixed  $alias
     * @param  mixed  $alias_type
     * @return $this
     */
    public function alias($alias, $alias_type = null)
    {
        $this->alias = $alias;

        if ($alias_type) {
            $this->alias_type = $alias_type;
        }

        return $this;
    }

    /**
     * 设置文件ID
     *
     * @param  mixed  $file_id
     * @return $this
     */
    public function fileId($file_id)
    {
        $this->file_id = $file_id;

        return $this;
    }

    /**
     * @param  array  $filter
     * @return $this
     */
    public function filter(array $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * 描述
     *
     * @param  string  $description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * 组合内容
     *
     * @return array
     *
     * @throws UmengPushException
     */
    public function toArray()
    {
        if (! $this->type) {
            throw new UmengPushException('消息发送类型必须设定');
        }

        return array_filter([
            'type' => $this->type,
            'device_tokens' => is_array($this->device_tokens) ? implode(',', $this->device_tokens) : $this->device_tokens,
            'alias_type' => $this->alias_type,
            'alias' => $this->alias,
            'file_id' => $this->file_id,
            'filter' => $this->filter,
            'payload' => $this->payload ? $this->payload->toArray() : null,
            'policy' => $this->policy ? $this->policy->toArray() : null,
            'description' => $this->description ?: null,
        ], function ($item) {
            return ! is_null($item);
        });
    }
}

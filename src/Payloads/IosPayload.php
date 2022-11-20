<?php

namespace Chuoke\UmengPush\Payloads;

use Chuoke\UmengPush\Contracts\Payload;
use Chuoke\UmengPush\Exceptions\UmengPushException;

class IosPayload implements Payload
{
    /** @var array 非静默推送时必填 */
    protected $alert = [];

    /** @var mixed 图标 */
    protected $badge;

    /** @var mixed 声音 */
    protected $sound;

    /** @var bool 是否静默推送 */
    protected $content_available = true;

    /** @var mixed iOS8才支持该字段 */
    protected $category;

    /** @var mixed 分组折叠，设置UNNotificationContent的threadIdentifier属性 */
    protected $thread_id;

    /** @var string 消息的打扰级别，iOS15起支持，四个选项"passive", "active", "time-sensitive", "critical" */
    protected $interruption_level;

    /** @var array 自定义参数 */
    protected $custom_params = [];

    /**
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
     * @param  string  $subtitle
     * @param  string  $title
     * @param  string  $body
     * @return $this
     */
    public function alert(string $title, string $body = '', string $subtitle = '')
    {
        $this->alert = [
            'title' => $title,
            'subtitle' => $subtitle,
            'body' => $body,
        ];

        return $this;
    }

    /**
     * @param  mixed  $badge
     * @return $this
     */
    public function badge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @param  mixed  $sound
     * @return $this
     */
    public function sound($sound)
    {
        $this->sound = $sound;

        return $this;
    }

    /**
     * 是否静默推送
     *
     * @param  bool  $content_available
     * @return $this
     */
    public function contentAvailable(bool $content_available)
    {
        $this->content_available = $content_available;

        return $this;
    }

    /**
     * iOS8才支持该字段
     *
     * @param  mixed  $category
     * @return $this
     */
    public function category($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * 分组折叠，设置UNNotificationContent的threadIdentifier属性
     *
     * @param  string  $threadId
     * @return $this
     */
    public function threadId($threadId)
    {
        $this->thread_id = $threadId;

        return $this;
    }

    /**
     * 消息的打扰级别，iOS15起支持，四个选项"passive", "active", "time-sensitive", "critical"
     *
     * @param  string  $level
     * @return $this
     */
    protected function interruptionLevel($level)
    {
        $this->interruption_level = $level;

        return $this;
    }

    /**
     * 设置消息打扰级别为 passive 消极的
     *
     * @return $this
     */
    public function passive()
    {
        return $this->interruptionLevel('passive');
    }

    /**
     * 设置消息打扰级别为 active 活跃的
     *
     * @return $this
     */
    public function active()
    {
        return $this->interruptionLevel('active');
    }

    /**
     * 设置消息打扰级别为 time-sensitive 高时效
     *
     * @return $this
     */
    public function timeSensitive()
    {
        return $this->interruptionLevel('time-sensitive');
    }

    /**
     * 设置消息打扰级别为 critical 危急的
     *
     * @return $this
     */
    public function critical()
    {
        return $this->interruptionLevel('critical');
    }

    /**
     * 设置自定义内容
     *
     * @param  array  $customParams
     * @return $this
     */
    public function customParams(array $customParams)
    {
        $this->custom_params = $customParams;

        return $this;
    }

    /**
     * 检查自定义参数是否有效
     *
     * @return void
     *
     * @throws UmengPushException
     */
    protected function checkCustomParams()
    {
        if ($this->custom_params) {
            $count = count($this->custom_params);
            $checkData = ['d' => 'd', 'p' => 'p'];
            if (count(array_merge($this->custom_params, $checkData)) != ($count + count($checkData))) {
                throw new UmengPushException(implode(',', array_keys($checkData)).'为友盟保留字段');
            }
        }
    }

    /**
     * 组合数据
     *
     * @return array
     *
     * @throws UmengPushException
     */
    public function toArray(): array
    {
        $this->checkCustomParams();

        $available = intval($this->content_available);
        if ($available != 1 && empty($this->alert)) {
            throw new UmengPushException('alert 内容不能为空');
        }

        $aps = array_filter([
            'alert' => $this->alert,
            'badge' => $this->badge,
            'sound' => $this->sound,
            'category' => $this->category,
            'thread-id' => $this->thread_id,
            'interruption-level' => $this->interruption_level,
        ]);
        $aps['content-available'] = $available;

        $data = $this->custom_params;
        $data['aps'] = $aps;

        return $data;
    }
}

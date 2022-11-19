<?php

namespace Chuoke\UmengPush\Policies;

use Chuoke\UmengPush\Contracts\Policy as PolicyInterface;

/**
 * 发送策略
 */
class Policy implements PolicyInterface
{
    /** @var string 定时发送，格式：yyyy-MM-dd HH:mm:ss */
    private $start_time;

    /** @var string 消息过期时间 */
    private $expire_time;

    /** @var string 开发者对消息的唯一标识，服务器会根据这个标识避免重复发送 */
    private $out_biz_no;

    public static function make()
    {
        return new static();
    }

    /**
     * 定时发送时，若不填写表示立即发送， 定时发送时间不能小于当前时间
     *
     * 注意，只对任务类消息生效
     *
     * @param string $startTime yyyy-MM-dd HH:mm:ss
     * @return $this
     */
    public function startTime(string $startTime)
    {
        $this->start_time = $startTime;
        return $this;
    }

    /**
     * 消息过期时间
     *
     * @param string $expireTime
     * @return $this
     */
    public function expireTime(string $expireTime)
    {
        $this->expire_time = $expireTime;
        return $this;
    }

    /**
     * 消息外部编号，用于消息发送接口对任务类消息的幂等性保证
     *
     * @param string $outBizNo
     * @return $this
     */
    public function outBizNo(string $outBizNo)
    {
        $this->out_biz_no = $outBizNo;
        return $this;
    }

    /**
     * 默认过期时间
     *
     * @return string
     */
    public function defaultExpireTime()
    {
        return date('Y-m-d H:i:s', strtotime('+3 days', $this->start_time ? strtotime($this->start_time) : time()));
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'start_time' => $this->start_time,
            'expire_time' => $this->expire_time ? $this->expire_time : $this->defaultExpireTime(),
            'out_biz_no' => $this->out_biz_no,
        ]);
    }
}

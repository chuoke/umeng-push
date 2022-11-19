<?php

namespace Chuoke\UmengPush\Policies;

/**
 * Android 发送策略
 */
class AndroidPolicy extends Policy
{

    /** @var int 发送限速，每秒发送的最大条数。最小值1000 */
    private $max_send_num;

    /**
     * 设置发送限速，每秒发送的最大条数
     *
     * @param int $maxSendNum
     * @return $this
     */
    public function maxSendNum(int $maxSendNum)
    {
        $this->max_send_num = $maxSendNum;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        if ($this->max_send_num) {
            $data['max_send_num'] = $this->max_send_num;
        }

        return $data;
    }
}

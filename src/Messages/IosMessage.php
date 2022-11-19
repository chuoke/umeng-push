<?php

namespace Chuoke\UmengPush\Messages;

use Chuoke\UmengPush\Payloads\IosPayload;
use Chuoke\UmengPush\Policies\IosPolicy;

class IosMessage extends Message
{
    /**
     * 具体消息内容
     *
     * @param  IosPayload  $payload
     * @return $this
     */
    public function payload(IosPayload $payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * 消息发送策略
     *
     * @param  IosPolicy  $payload
     * @return $this
     */
    public function policy(IosPolicy $policy)
    {
        $this->policy = $policy;
        return $this;
    }
}

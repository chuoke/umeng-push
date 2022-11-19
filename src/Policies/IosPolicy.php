<?php

namespace Chuoke\UmengPush\Policies;

/**
 * IOS 发送策略
 */
class IosPolicy extends Policy
{
    /** @var string 多条带有相同apns_collapse_id的消息，iOS设备仅展示 最新的一条，字段长度不得超过64bytes */
    private $apns_collapse_id;

    /**
     * iOS可用，多条带有相同apns_collapse_id的消息，iOS设备仅展示最新的一条，字段长度不得超过64bytes
     *
     * @param string $apnsCollapseId
     * @return $this
     */
    public function apnsCollapseId(string $apnsCollapseId)
    {
        $this->apns_collapse_id = $apnsCollapseId;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        if ($this->apns_collapse_id) {
            $data['apns_collapse_id'] = $this->apns_collapse_id;
        }

        return $data;
    }
}

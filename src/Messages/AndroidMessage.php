<?php

namespace Chuoke\UmengPush\Messages;

use Chuoke\UmengPush\Payloads\AndroidPayload;
use Chuoke\UmengPush\Policies\AndroidPolicy;

class AndroidMessage extends Message
{
    /** @var string 系统弹窗 */
    protected $channel_activity;

    /** @var string 小米通道 id */
    protected $xiaomi_channel_id;

    /** @var string vivo消息分类 */
    protected $vivo_classification;

    /** @var string oppo 通道id */
    protected $oppo_channel_id;

    /** @var string 应用入口Activity类全路径，主要用于华为通道角标展示 */
    protected $main_activity;

    /** @var string 华为消息分类 */
    protected $huawei_channel_importance;

    /** @var string 华为自分类消息类型 */
    protected $huawei_channel_category;

    /**
     * 系统弹窗，只有display_type=notification时有效，表示华为、小米、oppo、vivo、魅族的设备离线时走系统通道下发时打开指定页面acitivity的完整包路径。
     *
     * @param  string  $channelActivity
     * @return $this
     */
    public function channelActivity($channelActivity)
    {
        $this->channel_activity = $channelActivity;

        return $this;
    }

    /**
     * 小米channel_id，具体使用及限制请参考小米推送文档
     *
     * @see https://dev.mi.com/console/doc/detail?pId=2086 小米推送文档
     *
     * @param  string  $xiaomiChannelId
     * @return $this
     */
    public function xiaomiChannelId($xiaomiChannelId)
    {
        $this->xiaomi_channel_id = $xiaomiChannelId;

        return $this;
    }

    /**
     * vivo消息分类：0运营消息，1系统消息，需要到vivo申请，具体使用及限制参考[vivo消息推送分类功能说明]
     *
     * @see https://dev.vivo.com.cn/documentCenter/doc/359 vivo消息推送分类功能说明
     *
     * @param  string  $vivoClassification
     * @return $this
     */
    public function vivoClassification($vivoClassification)
    {
        $this->vivo_classification = $vivoClassification;

        return $this;
    }

    /**
     * android8 以上推送消息需要新建通道，否则消息无法触达用户。
     *
     * push sdk 6.0.5及以上创建了默认的通道:upush_default，消息提交厂商通道时默认添加该通道。
     * 如果要自定义通道名称或使用私信，请自行创建通道，推送消息时携带该参数具体可参考[oppo推送私信通道申请]
     *
     * @see https://open.oppomobile.com/new/developmentDoc/info?id=11227 oppo推送私信通道申请
     *
     * @param  string  $oppoChannelId
     * @return $this
     */
    public function oppoChannelId($oppoChannelId)
    {
        $this->oppo_channel_id = $oppoChannelId;

        return $this;
    }

    /**
     * 应用入口Activity类全路径,主要用于华为通道角标展示。具体使用可参考[华为角标使用说明]
     *
     * @see https://developer.umeng.com/docs/67966/detail/272597 华为角标使用说明
     *
     * @param  string  $mainActivity
     * @return $this
     */
    public function mainActivity($mainActivity)
    {
        $this->main_activity = $mainActivity;

        return $this;
    }

    /**
     * 华为消息分类 LOW：资讯营销类消息，NORMAL：服务与通讯类消息
     *
     * @param  string  $huaweiChannelImportance
     * @return $this
     */
    public function huaweiChannelImportance($huaweiChannelImportance)
    {
        $this->huawei_channel_importance = $huaweiChannelImportance;

        return $this;
    }

    /**
     * 华为自分类消息类型
     *
     * @see https://developer.huawei.com/consumer/cn/doc/development/HMSCore-Guides/message-priority-0000001181716924 华为消息分类
     *
     * @param  string  $huaweiChannelCategory
     * @return $this
     */
    public function huaweiChannelCategory($huaweiChannelCategory)
    {
        $this->huawei_channel_category = $huaweiChannelCategory;

        return $this;
    }

    /**
     * 具体消息内容
     *
     * @param  AndroidPayload  $payload
     * @return $this
     */
    public function payload(AndroidPayload $payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * 消息发送策略
     *
     * @param  AndroidPolicy  $payload
     * @return $this
     */
    public function policy(AndroidPolicy $policy)
    {
        $this->policy = $policy;

        return $this;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            // 厂商通道相关的特殊配置
            'channel_properties' => array_filter([
                'channel_activity' => $this->channel_activity,
                'xiaomi_channel_id' => $this->xiaomi_channel_id,
                'vivo_classification' => $this->vivo_classification,
                'oppo_channel_id' => $this->oppo_channel_id,
                'main_activity' => $this->main_activity,
                'huawei_channel_importance' => $this->huawei_channel_importance,
                'huawei_channel_category' => $this->huawei_channel_category,
            ], function ($item) {
                return ! is_null($item);
            }),
        ]);
    }
}

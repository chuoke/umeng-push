<?php

namespace Chuoke\UmengPush\Payloads;

use Chuoke\UmengPush\Contracts\Payload;
use Chuoke\UmengPush\Exceptions\UmengPushException;

class AndroidPayload implements Payload
{
    const TYPE_NOTIFICATION = 'notification';

    const TYPE_MESSAGE = 'message';

    const OPEN_APP = 'go_app';

    const OPEN_URL = 'go_url';

    const OPEN_ACTIVITY = 'go_activity';

    const OPEN_CUSTOM = 'go_custom';

    /** @var string 消息类型 notification(通知)、message(消息) */
    protected $display_type = 'notification';

    /** @var string 通知标题 */
    protected $title = '';

    /** @var string 通知文字描述 */
    protected $text = '';

    /** @var string 通知栏提示文字 */
    protected $ticker = '';

    /** @var string 最多120个字符大文本 */
    protected $big_body = '';

    /** @var string 自定义通知图标，状态栏图标ID，R.drawable.[smallIcon] */
    protected $icon;

    /** @var string 通知栏大图标的URL链接 */
    protected $img;

    /** @var string 消息下方展示大图，支持自有通道消息展示 */
    protected $expand_image;

    /** @var string 通知声音，R.raw.[sound] */
    protected $sound;

    /** @var int 自定义通知样式 */
    protected $builder_id = 0;

    /** @var mixed 角标，当前只支持华为厂商通道 */
    protected $badge;

    /** @var string 是否震动 */
    protected $play_vibrate = true;

    /** @var string 是否闪灯 */
    protected $play_lights = true;

    /** @var string 是否发出声音 */
    protected $play_sound = true;

    /**
     * @var string 点击"通知"的后续行为 默认为"go_app"，值可以为:
     *   "go_app": 打开应用
     *   "go_url": 跳转到URL
     *   "go_activity": 打开特定的activity
     *   "go_custom": 用户自定义内容。
     */
    protected $after_open = 'go_app';

    /** @var string 通知栏点击后跳转的URL，当after_open=go_url时必填 */
    protected $url;

    /** @var string 通知栏点击后打开的Activity，after_open=go_activity时必填 */
    protected $activity;

    /** @var array|string 用户自定义内容，当display_type=message时必填；当display_type=notification且after_open=go_custom时必填 */
    protected $custom;

    /** @var array 用户自定义key-value */
    protected $extra = [];

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        //
    }

    /**
     * @param  string  $display_type
     * @return $this
     */
    protected function displayType(string $display_type)
    {
        $this->display_type = $display_type;

        return $this;
    }

    /**
     * 消息类型: notification(通知)
     *
     * @return $this
     */
    public function notification()
    {
        return $this->displayType(self::TYPE_NOTIFICATION);
    }

    /**
     * 消息类型: message(消息)
     *
     * @return $this
     */
    public function message()
    {
        return $this->displayType(self::TYPE_MESSAGE);
    }

    /**
     * 设置通知标题
     *
     * @param  string  $title
     * @return $this
     */
    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * 设置通知文字描述
     *
     * @param  string  $text
     * @return $this
     */
    public function text(string $text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * 设置通知栏提示文字
     *
     * @param  string  $ticker
     * @return $this
     */
    public function ticker(string $ticker)
    {
        $this->ticker = $ticker;

        return $this;
    }

    /**
     * 最多120个字符大文本
     *
     * @param  string  $bigBody
     * @return $this
     */
    public function bigBody(string $bigBody)
    {
        $this->big_body = $bigBody;

        return $this;
    }

    /**
     * 状态栏图标ID，R.drawable.[smallIcon]
     *
     * @param  string  $icon
     * @return $this
     */
    public function icon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * 通知栏大图标的URL链接
     *
     * @param  string  $img
     * @return $this
     */
    public function img(string $img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * 消息下方展示大图，支持自有通道消息展示，厂商通道展示大图目前仅支持小米
     *
     * @param  string  $expandImage
     * @return $this
     */
    public function expandImage(string $expandImage)
    {
        $this->expand_image = $expandImage;

        return $this;
    }

    /**
     * 自定义通知声音，R.raw.[sound]
     *
     * @param  string  $sound
     * @return $this
     */
    public function sound($sound)
    {
        $this->sound = $sound;

        return $this;
    }

    /**
     * 自定义通知样式
     *
     * @param  mixed  $builder_id
     * @return $this
     */
    public function builderId($builder_id)
    {
        $this->builder_id = $builder_id;

        return $this;
    }

    /**
     * 角标，当前只支持华为厂商通道，范围为1-99
     *
     * @param  int  $badge
     * @return $this
     */
    public function badge(int $badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * 收到通知是否震动，默认为"true"
     *
     * @param  bool  $playVibrate
     * @return $this
     */
    public function playVibrate(bool $playVibrate)
    {
        $this->play_vibrate = $playVibrate;

        return $this;
    }

    /**
     * 收到通知是否闪灯，默认为"true"
     *
     * @param  bool  $playLights
     * @return $this
     */
    public function playLights(bool $playLights)
    {
        $this->play_lights = $playLights;

        return $this;
    }

    /**
     * 收到通知是否发出声音，默认为"true"
     *
     * @param  bool  $playSound
     * @return $this
     */
    public function playSound(bool $playSound)
    {
        $this->play_sound = $playSound;

        return $this;
    }

    /**
     * 击"通知"的后续行为(默认为打开app)
     *
     * @param  string  $after_open
     * @return $this
     */
    protected function afterOpen(string $after_open)
    {
        $this->after_open = $after_open;

        return $this;
    }

    /**
     * 点击"通知"打开app（默认）
     *
     * @return $this
     */
    public function afterGoApp()
    {
        return $this->afterOpen(static::OPEN_APP);
    }

    /**
     * 点击"通知"跳转到URL
     *
     * @param  string  $url 点击"通知"跳转的URL，要求以http或者https开头
     * @return $this
     */
    public function afterGoUrl(string $url)
    {
        $this->url = $url;

        return $this->afterOpen(static::OPEN_URL);
    }

    /**
     * 点击"通知"打开特定的activity
     *
     * @param  string  $activity 通知栏点击后打开的Activity
     * @return $this
     */
    public function afterGoActivity($activity)
    {
        $this->activity = $activity;

        return $this->afterOpen(static::OPEN_ACTIVITY);
    }

    /**
     * 点击"通知"打开用户自定义内容
     *
     * @param  array|string  $custom 用户自定义内容，可以为字符串或者JSON格式。
     * @return $this
     */
    public function afterGoCustom($custom = '')
    {
        if ($custom) {
            $this->custom($custom);
        }

        return $this->afterOpen(static::OPEN_CUSTOM);
    }

    /**
     * 用户自定义内容，
     * 当display_type=message时必填；
     * 当display_type=notification且after_open=go_custom时必填；
     *
     * @param  array|string  $custom
     * @return $this
     */
    public function custom($custom)
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * 设置用户自定义key-value，可以配合消息到达后，打开App/URL/Activity使用
     *
     * @param  array  $extra
     * @return $this
     */
    public function extra(array $extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * 组合参数
     *
     * @return array
     *
     * @throws UmengPushException
     */
    public function toArray(): array
    {
        return array_filter([
            'display_type' => $this->display_type,
            'body' => $this->collectBody(),
            'extra' => $this->extra,
        ]);
    }

    /**
     * @return array
     *
     * @throws UmengPushException
     */
    public function collectBody()
    {
        $this->checkParams();

        $validTitle = $this->getValidTitle();

        return array_filter([
            'title' => $this->title ?: $validTitle,
            'text' => $this->text ?: $validTitle,
            'ticker' => $this->ticker,
            'big_body' => $this->big_body,
            'icon' => $this->icon,
            'img' => $this->img,
            'expand_image' => $this->expand_image,
            'sound' => $this->sound,
            'builder_id' => $this->builder_id,
            'badge' => $this->badge,
            'play_vibrate' => $this->bool2str($this->play_vibrate),
            'play_lights' => $this->bool2str($this->play_lights),
            'play_sound' => $this->bool2str($this->play_sound),
            'after_open' => $this->after_open,
            'url' => $this->url,
            'activity' => $this->activity,
            'custom' => $this->custom,
            'extra' => $this->extra,
        ]);
    }

    /**
     * 获取有效的标题
     *
     * @return string
     */
    public function getValidTitle()
    {
        return $this->title ?: ($this->text ?: $this->ticker);
    }

    /**
     * 检查验证参数
     *
     * @return void
     *
     * @throws UmengPushException
     */
    public function checkParams()
    {
        if ($this->display_type == self::TYPE_MESSAGE && ! $this->custom) {
            throw new UmengPushException('该类型通知用户自定义内容custom必须提供');
        }

        switch ($this->after_open) {
            case self::OPEN_URL:
                if (! $this->url || stripos('http', $this->url) !== 0) {
                    throw new UmengPushException('点击通知后跳转的URL必须提供，并以http或者https开头');
                }
                break;
            case self::OPEN_ACTIVITY:
                if (! $this->activity) {
                    throw new UmengPushException('点击通知后打开的Activity必须提供');
                }
                break;
            case self::OPEN_CUSTOM:
                if ($this->display_type === self::TYPE_NOTIFICATION && ! $this->custom) {
                    throw new UmengPushException('该类型通知用户自定义内容custom必须提供');
                }
                break;
        }
    }

    /**
     * 把bool值转为字符串形式
     *
     * @param  mixed  $val
     * @return string
     */
    public function bool2str($val)
    {
        return $val ? 'true' : 'false';
    }
}

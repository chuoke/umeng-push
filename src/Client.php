<?php

namespace Chuoke\UmengPush;

use Chuoke\UmengPush\Contracts\Message;
use Chuoke\UmengPush\Exceptions\UmengPushException;
use Chuoke\UmengPush\Contracts\Client as ClientInterface;

class Client implements ClientInterface
{
    /** @var Config */
    protected $config;

    public function __construct(Config $config = null)
    {
        $this->config = $config;
    }

    /**
     * 实例化
     *
     * @param Config|null $config
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
        return $this;
    }

    /**
     * 基础参数
     *
     * @return array
     */
    public function commonParams()
    {
        return [
            'appkey' => $this->config->app_key,
            'timestamp' => time(),
        ];
    }

    /**
     * 发送消息
     *
     * @see https://developer.umeng.com/docs/67966/detail/68343#h1-u6D88u606Fu53D1u90014
     * @param  Message  $message
     * @return Response
     */
    public function send(Message $message)
    {
        return $this->request('/api/send', array_merge(
            $this->commonParams(),
            $message->toArray(),
            [
                'production_mode' => $this->config->production_mode,
            ]
        ));
    }

    /**
     * 消息状态查询
     *
     * @see https://developer.umeng.com/docs/67966/detail/68343#h1-u4EFBu52A1u7C7Bu6D88u606Fu72B6u6001u67E5u8BE25
     * @param  mixed  $taskId
     * @return Response
     * @throws UmengPushException
     */
    public function status($taskId)
    {
        return $this->request('/api/status', array_merge($this->commonParams(), [
            'task_id' => $taskId,
        ]));
    }

    /**
     * 任务送达数据查询
     *
     * @see https://developer.umeng.com/docs/67966/detail/68343#p-27x-xes-qz2
     * @param  mixed  $taskId
     * @return Response
     * @throws UmengPushException
     */
    public function taskStat($taskId)
    {
        return $this->request('/api/task/stat', array_merge($this->commonParams(), [
            'task_id' => $taskId,
        ]));
    }

    /**
     * 消息撤销
     *
     * @see https://developer.umeng.com/docs/67966/detail/68343#h1-u4EFBu52A1u7C7Bu6D88u606Fu53D6u6D886
     * @param  mixed  $taskId
     * @return Response
     */
    public function cancel($taskId)
    {
        return $this->request('/api/cancel', array_merge($this->commonParams(), [
            'task_id' => $taskId,
        ]));
    }

    /**
     * 文件上传
     *
     * @see https://developer.umeng.com/docs/67966/detail/68343#h1-u6587u4EF6u4E0Au4F207
     * @param  string|array  $content
     * @return Response
     */
    public function upload($content)
    {
        return $this->request('/upload', array_merge($this->commonParams(), [
            'content' => is_array($content) ? implode("\n", $content) : $content,
        ]));
    }

    /**
     * 生成签名
     *
     * @param  string  $url
     * @param  string  $body
     * @return Response
     */
    protected function makeSign($url, $body)
    {
        return md5('POST' . $url . $body . $this->config->app_secret);
    }

    /**
     * 请求
     *
     * @param  string  $api
     * @param  array  $data
     * @return Response|mixed
     */
    public function request($api, array $data)
    {
        if (!$this->config || !$this->config->app_key || !$this->config->app_secret) {
            throw new UmengPushException('缺少必要的配置项');
        }

        $url = $this->config->base_url . $api;
        $sign = $this->makeSign($url, $postBody = json_encode($data));
        $url = $url . '?sign=' . $sign;

        return $this->doRequest($url, $postBody);
    }

    /**
     * 执行请求
     *
     * @param  string  $url
     * @param  string  $postBody
     * @return Response
     */
    public function doRequest($url, $postBody)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErrNo = curl_errno($ch);
        $curlErr = curl_error($ch);
        curl_close($ch);

        $response = new Response($result);

        if ($httpCode == '0' || $curlErr) {
            $response->setError("Curl error:[{$curlErrNo}] {$curlErr}");
        } elseif ($httpCode != '200') {
            $response->setError("Http code: [{$httpCode}] {$result}");
        }

        return new Response($result);
    }
}

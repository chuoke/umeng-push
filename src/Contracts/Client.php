<?php

namespace Chuoke\UmengPush\Contracts;

use Chuoke\UmengPush\Config;
use Chuoke\UmengPush\Exceptions\UmengPushException;

interface Client
{
    /**
     * 设置配置信息
     *
     * @param  Config  $config
     * @return $this
     */
    public function config(Config $config);

    /**
     * 发送消息
     *
     * @see https://developer.umeng.com/docs/67966/detail/68343#h1-u6D88u606Fu53D1u90014
     *
     * @param  Message  $message
     * @return Response
     *
     * @throws UmengPushException
     */
    public function send(Message $message);

    /**
     * 消息状态查询
     *
     * @see https://developer.umeng.com/docs/67966/detail/68343#h1-u4EFBu52A1u7C7Bu6D88u606Fu72B6u6001u67E5u8BE25
     *
     * @param  string  $taskId
     * @return Response
     *
     * @throws UmengPushException
     */
    public function status($taskId);

    /**
     * 任务送达数据查询
     *
     * @see https://developer.umeng.com/docs/67966/detail/68343#p-27x-xes-qz2
     *
     * @param  string  $taskId
     * @return Response
     *
     * @throws UmengPushException
     */
    public function taskStat($taskId);

    /**
     * 消息撤销
     *
     * @see https://developer.umeng.com/docs/67966/detail/68343#h1-u4EFBu52A1u7C7Bu6D88u606Fu53D6u6D886
     *
     * @param  string  $taskId
     * @return Response
     */
    public function cancel($taskId);

    /**
     * 文件上传
     *
     * @see https://developer.umeng.com/docs/67966/detail/68343#h1-u6587u4EF6u4E0Au4F207
     *
     * @param  string|array  $content
     * @return Response
     *
     * @throws UmengPushException
     */
    public function upload($content);
}

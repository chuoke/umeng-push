<?php

use Chuoke\UmengPush\Config;
use Chuoke\UmengPush\Messages\IosMessage;
use Chuoke\UmengPush\Payloads\IosPayload;
use Chuoke\UmengPush\Policies\IosPolicy;
use Chuoke\UmengPush\Response;
use Chuoke\UmengPush\UmengPusher;

it('can ability to initiate requests with ios message', function () {
    $pusher = UmengPusher::make(Config::make(umeng_push_config()));

    $message = IosMessage::make()->payload(IosPayload::make()->alert('测试'))->unicast();

    $response = $pusher->send($message);

    expect($response instanceof Response)->toEqual(true);

    expect($response->data('error_code'))->toEqual('2000');
});

it('is ios policy usable', function () {
    $pushTime = '2022-11-19 19:54:30';
    $policy = IosPolicy::make()->startTime($pushTime);

    $policyResult = $policy->toArray();

    expect($policyResult['start_time'])->toEqual($pushTime);
});

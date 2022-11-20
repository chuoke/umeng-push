<?php

use Chuoke\UmengPush\Config;
use Chuoke\UmengPush\Messages\AndroidMessage;
use Chuoke\UmengPush\Payloads\AndroidPayload;
use Chuoke\UmengPush\Policies\AndroidPolicy;
use Chuoke\UmengPush\Response;
use Chuoke\UmengPush\UmengPusher;

it('can ability to initiate requests with android message', function () {
    $pusher = UmengPusher::make(Config::make(umeng_push_config()));

    $message = AndroidMessage::make()->payload(AndroidPayload::make()->title('测试'))->unicast();

    $response = $pusher->send($message);

    expect($response instanceof Response)->toEqual(true);

    expect($response->data('error_code'))->toEqual('6005');
});

it('is android policy usable', function () {
    $pushTime = '2022-11-19 19:54:30';
    $policy = AndroidPolicy::make()->startTime($pushTime);

    $policyResult = $policy->toArray();

    expect($policyResult['start_time'])->toEqual($pushTime);
});

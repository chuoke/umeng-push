<?php

use Chuoke\UmengPush\Config;

it('can test', function () {
    expect(true)->toBeTrue();
});

it('is production mode work', function () {
    $trueModeConfig = Config::make(array_merge(umeng_push_config(), [
        'production_mode' => true,
    ]));

    expect($trueModeConfig->production_mode)->toEqual('true');

    $falseModeConfig = Config::make(array_merge(umeng_push_config(), [
        'production_mode' => false,
    ]));

    expect($falseModeConfig->production_mode)->toEqual('false');
});

<?php

namespace Chuoke\UmengPush\Exceptions;

use Exception;

class UmengPushException extends Exception
{
    public function __construct($msg, $code = 0)
    {
        parent::__construct($msg, $code);
    }
}

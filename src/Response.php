<?php

namespace Chuoke\UmengPush;

class Response
{
    /** @var string 原始响应信息 */
    protected $original;

    /** @var array 解析后的响应信息 */
    protected $response;

    /** @var string 错误信息 */
    protected $error;

    public function __construct($original)
    {
        $this->original = $original;

        $this->response = json_decode($original, true) ?: [];
    }

    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        return $this->retIs('SUCCESS');
    }

    /**
     * @return bool
     */
    public function isFail()
    {
        return $this->retIs('FAIL');
    }

    /**
     * 返回状态标记判断
     *
     * @param  string  $flag
     * @return bool
     */
    protected function retIs($flag)
    {
        return ! $this->response || $this->response['ret'] === $flag;
    }

    /**
     * 获取返回的原始信息
     *
     * @return string
     */
    public function original()
    {
        return $this->original;
    }

    /**
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function data($key = null, $default = null)
    {
        if (! $this->response) {
            return $default;
        }

        if (is_null($key)) {
            return $this->response;
        }

        $data = $this->response['data'];
        foreach (explode('.', $key) as $k) {
            if (! array_key_exists($k, $data)) {
                return $default;
            }

            $data = $data[$k];
        }

        return $data;
    }

    public function getErrorCode()
    {
        return $this->data('error_code') ?: null;
    }

    public function getErrorMsg()
    {
        return $this->data('error_msg') ?: $this->error;
    }
}

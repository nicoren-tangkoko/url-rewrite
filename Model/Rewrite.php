<?php

namespace MageSuite\UrlRewrite\Model;

class Rewrite
{
    /**
     * @var string
     */
    protected $targetUrl;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return mixed
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * @param mixed $targetUrl
     */
    public function setTargetUrl($targetUrl)
    {
        $this->targetUrl = $targetUrl;
    }
}
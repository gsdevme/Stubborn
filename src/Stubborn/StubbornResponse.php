<?php

namespace Stubborn;

class StubbornResponse implements StubbornResponseInterface
{

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var int
     */
    private $httpCode;

    /**
     * @var int
     */
    private $retryCount;

    /**
     * @inheritdoc
     */
    public function __construct($data, $httpCode)
    {
        $this->data     = $data;
        $this->httpCode = (int)$httpCode;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @param int $retryCount
     */
    public function setRetryCount($retryCount)
    {
        $this->retryCount = (int)$retryCount;
    }

    /**
     * @return int
     */
    public function getRetryCount()
    {
        return $this->retryCount;
    }
}

<?php

namespace Stubborn;

class StubbornResponse
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
     * @param mixed $data
     * @param int $httpCode
     */
    public function __construct($data, $httpCode)
    {
        $this->data = $data;
        $this->httpCode = $httpCode;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }
}

<?php

namespace Stubborn;

interface StubbornResponseInterface
{

    /**
     * @param mixed $data
     * @param int $httpCode
     */
    public function __construct($data, $httpCode);

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return int
     */
    public function getHttpCode();

    public function setRetryCount($retryCount);
    public function getRetryCount();
}

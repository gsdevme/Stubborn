<?php

namespace Stubborn;

interface StubbornResponseInterface
{

    /**
     * @param mixed $data
     * @param int $httpCode
     * @return void
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

    /**
     * @return void
     */
    public function setRetryCount($retryCount);

    /**
     * @return integer
     */
    public function getRetryCount();
}

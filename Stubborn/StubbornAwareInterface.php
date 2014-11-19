<?php

namespace Stubborn;

interface StubbornAwareInterface
{
    const STOP_EVENT = 0;
    const RETRY_EVENT = 1;

    /**
     * @return int|null
     */
    public function getRetryNumber();

    /**
     * @return StubbornResponse|1|0|null
     */
    public function run();

    /**
     * @param StubbornResponse $response
     * @return false|1|0
     */
    public function getHttpActionRequest(StubbornResponse $response);
}

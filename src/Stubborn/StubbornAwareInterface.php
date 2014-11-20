<?php

namespace Stubborn;

interface StubbornAwareInterface
{
    const STOP_EVENT = 0;
    const RETRY_EVENT = 1;
    const RETRY_WAIT_EVENT = 2;

    /**
     * @return int|null
     */
    public function getRetryNumber();

    /**
     * @return int|null
     */
    public function getRetryWaitSeconds();

    /**
     * @return StubbornResponse|2|1|0|null
     */
    public function run();

    /**
     * @param StubbornResponseInterface $response
     * @return false|2|1|0
     */
    public function getHttpActionRequest(StubbornResponseInterface $response);

    /**
     * @param \Exception $exception
     * @return false|2|1|0
     */
    public function getExceptionActionRequest(\Exception $exception);
}

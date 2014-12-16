<?php

namespace Stubborn;

interface StubbornAwareInterface
{
    const STOP_ACTION       = 0;
    const RETRY_ACTION      = 1;
    const RETRY_WAIT_ACTION = 2;

    /**
     * @return int|null
     */
    public function getRetryNumber();

    /**
     * @return int|null
     */
    public function getRetryWaitSeconds();

    /**
     * @return StubbornResponse|int|null
     */
    public function run();

    /**
     * @param StubbornResponseInterface $response
     * @return false|int
     */
    public function getHttpActionRequest(StubbornResponseInterface $response);

    /**
     * @param \Exception $exception
     * @return false|int
     */
    public function getExceptionActionRequest(\Exception $exception);
}

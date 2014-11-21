<?php

namespace Stubborn;

interface StubbornAwareInterface
{
    const STOP_ACTION = 0;
    const RETRY_ACTION = 1;
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
     * @return StubbornResponse|self::RETRY_WAIT_ACTION|self::RETRY_ACTION|self::STOP_ACTION|null
     */
    public function run();

    /**
     * @param StubbornResponseInterface $response
     * @return false|self::RETRY_WAIT_ACTION|self::RETRY_ACTION|self::STOP_ACTION
     */
    public function getHttpActionRequest(StubbornResponseInterface $response);

    /**
     * @param \Exception $exception
     * @return false|self::RETRY_WAIT_ACTION|self::RETRY_ACTION|self::STOP_ACTION
     */
    public function getExceptionActionRequest(\Exception $exception);
}

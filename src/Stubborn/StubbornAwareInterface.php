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
     * @return StubbornResponse|StubbornAwareInterface::RETRY_WAIT_ACTION|StubbornAwareInterface::RETRY_ACTION|StubbornAwareInterface::STOP_ACTION|null
     */
    public function run();

    /**
     * @param StubbornResponseInterface $response
     * @return false|StubbornAwareInterface::RETRY_WAIT_ACTION|StubbornAwareInterface::RETRY_ACTION|StubbornAwareInterface::STOP_ACTION
     */
    public function getHttpActionRequest(StubbornResponseInterface $response);

    /**
     * @param \Exception $exception
     * @return false|StubbornAwareInterface::RETRY_WAIT_ACTION|StubbornAwareInterface::RETRY_ACTION|StubbornAwareInterface::STOP_ACTION
     */
    public function getExceptionActionRequest(\Exception $exception);
}

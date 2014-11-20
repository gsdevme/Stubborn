<?php

namespace Stubborn;

/**
 * Optional abstract if you wish to use it, defines common sense values
 *
 * @package Stubborn
 */
abstract class StubbornAbstract implements StubbornAwareInterface
{

    /**
     * @inheritdoc
     */
    public function getRetryNumber()
    {
        return 2;
    }

    /**
     * @inheritdoc
     */
    public function getRetryWaitSeconds()
    {
        return 5;
    }

    public function getExceptionActionRequest(\Exception $exception)
    {
        // Default action is to just rethrow the Exception, we don't know what to do with it
        throw $exception;
    }

}

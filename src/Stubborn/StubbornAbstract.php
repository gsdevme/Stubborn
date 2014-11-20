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
}

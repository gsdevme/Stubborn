<?php

namespace Something;

use Stubborn\StubbornAwareInterface;

abstract class SomethingAbstract implements StubbornAwareInterface
{

    /**
     * @inheritdoc
     */
    public function getRetryNumber()
    {
        return 4;
    }

    /**
     * @inheritdoc
     */
    public function getRetryWaitSeconds()
    {
        return 5;
    }
}

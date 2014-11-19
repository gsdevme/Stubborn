<?php

namespace Something;

use Stubborn\StubbornAwareInterface;

abstract class SomethingAbstract implements StubbornAwareInterface
{

    public function getRetryNumber()
    {
        return 4;
    }
}

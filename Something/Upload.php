<?php

namespace Something;

use Stubborn\StubbornResponse;

class Upload extends SomethingAbstract
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        /**
         * Lets say in this example the API fucked up and we want to stop it
         */
        return self::STOP_EVENT;
    }

    /**
     * @inheritdoc
     */
    public function getHttpActionRequest(StubbornResponse $response)
    {
        // Cba checking the http code
        return false;
    }

    public function getExceptionActionRequest(\Exception $exception)
    {
        return self::STOP_EVENT;
    }
}

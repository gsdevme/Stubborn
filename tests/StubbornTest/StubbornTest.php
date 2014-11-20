<?php

    namespace StubbornTest;

    use Stubborn\Stubborn;
    use PHPUnit_Framework_TestCase;
    use PHPUnit_Framework_Error;
    use stdClass;
    use Stubborn\StubbornAwareInterface;

    class StubbornTest extends PHPUnit_Framework_TestCase
    {

        public function testConstructTypeHint()
        {
            new Stubborn($this->getStubbornMock());
            $this->assertTrue(true);
        }

        /**
         * @expectedException \Stubborn\Exception\TooManyRetriesException
         */
        public function testTooManyRetriesException()
        {
            $stubbornAwareObject = $this->getStubbornMock();

            // Setup $stubbornAwareObject
            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(3));
            $stubbornAwareObject->method('getRetryWaitSeconds')->will($this->returnValue(0));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('run')->will($this->returnValue(StubbornAwareInterface::RETRY_EVENT));

            $stubborn = new Stubborn($stubbornAwareObject);
            $stubborn->run();
        }

        private function getStubbornMock()
        {
            return $this->getMock(
                'Stubborn\StubbornAwareInterface',
                [
                    'getRetryNumber',
                    'getRetryWaitSeconds',
                    'run',
                    'getHttpActionRequest',
                    'getExceptionActionRequest'
                ],
                [],
                'StubbornAwareObject'
            );
        }

        /*private function getStubbornResponseMock()
        {
            return $this->getMock(
                'Stubborn\StubbornResponseInterface',
                [
                    '__construct',
                    'getData',
                    'getHttpCode',
                    'setRetryCount',
                    'getRetryCount',
                ],
                [],
                'StubbornResponseObject'
            );
        }*/
    }

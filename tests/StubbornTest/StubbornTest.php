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
         * Tests if we retry past our limit it throws an exception
         *
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

        /**
         * @expectedException \Stubborn\Exception\TooManyRetriesException
         */
        public function testDefaultSleep()
        {
            $stubbornAwareObject = $this->getStubbornMock();

            // Setup $stubbornAwareObject
            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(5));
            $stubbornAwareObject->method('getRetryWaitSeconds')->will($this->returnValue(null));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('run')->will($this->returnValue(StubbornAwareInterface::RETRY_WAIT_EVENT));

            $stubborn = new Stubborn($stubbornAwareObject);
            $this->assertNull($stubborn->run());
        }

        /**
         * Tests if we can Stop the ->run
         */
        public function testResponseStop()
        {
            $stubbornAwareObject = $this->getStubbornMock();

            // Setup $stubbornAwareObject
            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(5));
            $stubbornAwareObject->method('getRetryWaitSeconds')->will($this->returnValue(0));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('run')->will($this->returnValue(StubbornAwareInterface::STOP_EVENT));

            $stubborn = new Stubborn($stubbornAwareObject);
            $this->assertNull($stubborn->run());
        }

        /**
         * Tests if the number of retries is correct by counting on the callback on ->run
         */
        public function testResponseRetry()
        {
            $stubbornAwareObject = $this->getStubbornMock();

            $count = 0;

            // Run it 34 times
            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(33));

            $stubbornAwareObject->method('getRetryWaitSeconds')->will($this->returnValue(5));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnValue(false));

            $stubbornAwareObject->method('run')->will($this->returnCallback(function () use (&$count) {
                $count += 1;

                return StubbornAwareInterface::RETRY_EVENT;
            }));

            try{
                $stubborn = new Stubborn($stubbornAwareObject);
                $this->assertNull($stubborn->run());
            }catch(\Exception $e){

            }

            $this->assertEquals(33, $count);
        }

        /**
         * Tests the retry wait is used
         *
         * @large
         */
        public function testResponseRetryWait()
        {
            $stubbornAwareObject = $this->getStubbornMock();

            $count = 0;

            // Run it 6 times
            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(5));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnValue(false));

            $stubbornAwareObject->method('getRetryWaitSeconds')->will($this->returnCallback(function () {
                return 1;
            }));

            $stubbornAwareObject->method('run')->will($this->returnCallback(function () use (&$count) {
                $count += 1;

                return StubbornAwareInterface::RETRY_WAIT_EVENT;
            }));

            try{
                $stubborn = new Stubborn($stubbornAwareObject);
                $this->assertNull($stubborn->run());
            }catch(\Exception $e){

            }

            $this->assertEquals(5, $count);
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

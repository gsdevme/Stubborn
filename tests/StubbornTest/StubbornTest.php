<?php

    namespace StubbornTest;

    use PHPUnit_Framework_TestCase;
    use SebastianBergmann\Exporter\Exception;
    use Stubborn\Stubborn;
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

            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(3));
            $stubbornAwareObject->method('getRetryWaitSeconds')->will($this->returnValue(0));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnValue(false));

            $stubbornAwareObject->expects($this->exactly(4))
                ->method('run')
                ->will($this->returnValue(StubbornAwareInterface::RETRY_ACTION));

            $stubborn = new Stubborn($stubbornAwareObject);
            $stubborn->run();
        }

        /**
         * @expectedException \Stubborn\Exception\TooManyRetriesException
         */
        public function testTooManyRetriesExceptionWithNullRetryWaitSecond()
        {
            $stubbornAwareObject = $this->getStubbornMock();

            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(5));
            $stubbornAwareObject->method('getRetryWaitSeconds')->will($this->returnValue(null));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnValue(false));

            $stubbornAwareObject->expects($this->exactly(6))
                ->method('run')
                ->will($this->returnValue(StubbornAwareInterface::RETRY_WAIT_ACTION));

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

            $stubbornAwareObject->expects($this->exactly(1))
                ->method('run')
                ->will($this->returnValue(StubbornAwareInterface::STOP_ACTION));

            $stubborn = new Stubborn($stubbornAwareObject);

            $this->assertNull($stubborn->run());
        }

        public function testExceptionActionRequest()
        {
            $stubbornAwareObject = $this->getStubbornMock();

            // Setup $stubbornAwareObject
            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(5));
            $stubbornAwareObject->method('getRetryWaitSeconds')->will($this->returnValue(0));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnCallback(function () {
                return StubbornAwareInterface::STOP_ACTION;
            }));

            $stubbornAwareObject->expects($this->exactly(1))
                ->method('run')
                ->will($this->returnCallback(function () {
                    throw new Exception();
                }));

            $stubborn = new Stubborn($stubbornAwareObject);

            $this->assertNull($stubborn->run());
        }

        /**
         * Tests if the number of retries is correct by counting on the callback on ->run
         */
        public function testResponseRetry()
        {
            $stubbornAwareObject = $this->getStubbornMock();

            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(33));

            $stubbornAwareObject->method('getRetryWaitSeconds')->will($this->returnValue(null));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnValue(false));

            $stubbornAwareObject->expects($this->exactly(34))
                ->method('run')
                ->will($this->returnValue(StubbornAwareInterface::RETRY_ACTION));

            try{
                $stubborn = new Stubborn($stubbornAwareObject);
                $this->assertNull($stubborn->run());
            }catch(\Exception $e){

            }
        }

        /**
         * Tests the retry wait is used
         *
         * ->getRetryNumber returns 3
         * ->getRetryWaitSeconds should be called 6 times
         * ->run should be called 4 times
         *
         * @large
         */
        public function testResponseRetryWait()
        {
            $stubbornAwareObject = $this->getStubbornMock();

            // Run it 4 times
            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(3));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnValue(false));

            $stubbornAwareObject->expects($this->any())
                ->method('getRetryWaitSeconds')
                ->will($this->returnValue(1));

            $stubbornAwareObject->expects($this->exactly(4))
                ->method('run')
                ->will($this->returnValue(StubbornAwareInterface::RETRY_WAIT_ACTION));

            try{
                $stubborn = new Stubborn($stubbornAwareObject);
                $this->assertNull($stubborn->run());
            }catch(\Exception $e){

            }
        }

        /**
         * Tests that when the ->run returns a Stubburn response object it will correctly return it and end the loop
         */
        public function testResponseStubbornMockZeroRetries()
        {
            $stubbornAwareObject = $this->getStubbornMock();
            $stubbornResponseObject = $this->getStubbornResponseMock();

            $stubbornResponseObject
                ->expects($this->once())->method('getData')
                ->will($this->returnValue('{"status":"true"}'));

            $stubbornResponseObject
                ->expects($this->once())->method('getHttpCode')
                ->will($this->returnValue(200));

            // Run it 4 times
            $stubbornAwareObject->method('getRetryNumber')->will($this->returnValue(5));
            $stubbornAwareObject->method('getHttpActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getExceptionActionRequest')->will($this->returnValue(false));
            $stubbornAwareObject->method('getRetryWaitSeconds')->will($this->returnValue(0));

            $stubbornAwareObject->expects($this->once())
                ->method('run')
                ->will($this->returnValue($stubbornResponseObject));

            $stubborn = new Stubborn($stubbornAwareObject);
            $response = $stubborn->run();

            $this->assertInstanceOf('Stubborn\StubbornResponseInterface', $response);
            $this->assertEquals(0, $response->getRetryCount());

            $this->assertEquals('{"status":"true"}', $response->getData());
            $this->assertEquals(200, $response->getHttpCode());
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

        private function getStubbornResponseMock()
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
        }
    }

<?php

namespace Stubborn;

class Stubborn
{

    /**
     * @var StubbornAwareInterface
     */
    private $stubborn;

    /**
     * @param StubbornAwareInterface $stubborn
     */
    public function __construct(StubbornAwareInterface $stubborn)
    {
        $this->stubborn = $stubborn;
    }

    /**
     * @return StubbornResponse|null
     * @throws Exception\TooManyRetriesException|Exception
     */
    public function run()
    {
        $maxRetries = $this->stubborn->getRetryNumber();

        $retry = true;
        $retries = 0;

        while($retry === true){
            $response = null;
            $action = null;

            try{
                // Run the 'callback' the user wants
                $response = $this->stubborn->run();
            }catch(\Exception $e){
                $action = $this->stubborn->getExceptionActionRequest($e);
            }

            // Did we get a StubbornResponse back?
            if($response instanceof StubbornResponseInterface){
                // Lets check our HTTP Code, do we need to do anything?
                $action = $this->stubborn->getHttpActionRequest($response);
            }

            if(isset($action)){
                // No action is required
                if($action === false){
                    $response->setRetryCount($retries);

                    return $response;
                }

                // Assign the action to the response, this will drop into the code below
                $response = $action;
            }

            // Fallback to checking if we got a Retry or Stop
            switch($response){
                case StubbornAwareInterface::RETRY_WAIT_ACTION:
                    // Break the switch and continue the loop
                    $retries += 1;

                    $this->sleep($this->getRetryWaitSeconds());
                    break;
                case StubbornAwareInterface::RETRY_ACTION:
                    // Break the switch and continue the loop
                    $retries += 1;
                    break;
                case StubbornAwareInterface::STOP_ACTION:
                default:
                    // Return the function and stop the loop
                    return null;
            }

            if($retries > $maxRetries){
                throw new Exception\TooManyRetriesException(get_class($this->stubborn) . '->run() has reached the maximum allowed retries.');
            }
        }

        return null;
    }

    /**
     * Quickly checks if zero, Im not sure if PHP handles sleep(0) well.. so just done this for now
     *
     * @param $v
     */
    private function sleep($v)
    {
        if($v > 0){
            sleep($v);
        }
    }

    /**
     * @return int
     */
    private function getRetryWaitSeconds()
    {
        if($this->stubborn->getRetryWaitSeconds() !== null){
            return (int)$this->stubborn->getRetryWaitSeconds();
        }

        return 0;
    }
}

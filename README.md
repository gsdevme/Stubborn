## Stubborn

[![Build Status](https://travis-ci.org/gsdevme/Stubborn.svg?branch=master)](https://travis-ci.org/gsdevme/Stubborn)

#### Idea taken from
http://www.reddit.com/r/PHP/comments/2mqyxw/a_little_library_i_wrote_for_dealing_with/

### Concept
For each API vendor (e.g. Facebook, Twitter, Dropbox) have the requests implement StubbornAwareInterface and throw the class into Stubborn, You can implement the methods to handle the API oddities

### Example
```php
class StubbornDummyApi implements \Stubborn\StubbornAwareInterface
{

    private $apiKey;

    public function __construct($apikey)
    {
        $this->apiKey = $apiKey;
    }

    public function getRetryNumber()
    {
        // try twice
        return 1;
    }

    public function getRetryWaitSeconds()
    {
        // wait 5 seconds after each try
        return 5;
    }

    public function run()
    {
        // use the API key or something in the real world?
        $this->apiKey = null;

        // Actual API logic here.. so Facebook, Twitter etc
        return new \Stubborn\StubbornResponse('{"status":true}', 200);
    }

    public function getHttpActionRequest(\Stubborn\StubbornResponseInterface $response)
    {
        // Handle the HTTP code, 501/408 lets retry
        switch($response->getHttpCode()){
            case 501:
            case 408:
                return self::RETRY_EVENT;
            default:
                return false;
        }
    }

    public function getExceptionActionRequest(\Exception $exception)
    {
        return false;
    }
}

$dummyApiRequest = new StubbornDummyApi('123456789qwerty');
$stubborn = new Stubborn\Stubborn($dummyApiRequest);
$result = $stubborn->run();
```

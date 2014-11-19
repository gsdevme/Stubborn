<?php

namespace Something;

use Stubborn\StubbornResponse;

class Like extends SomethingAbstract
{

    private $apiToken;
    private $id;

    /**
     * @param $apiToken
     * @param $id
     */
    public function __construct($apiToken, $id)
    {
        $this->apiToken = $apiToken;
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        /**
         * Example uses the ApiToken & Id to like something and return the JSON return from the API
         */
        return new StubbornResponse('{status: true}', 201);
    }

    /**
     * @inheritdoc
     */
    public function getHttpActionRequest(StubbornResponse $response)
    {
        switch($response->getHttpCode()){
            case 404:
                return self::RETRY_EVENT;
            case 200:
            case 201:
            default:
                return false;
        }
    }
}

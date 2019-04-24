<?php
/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-24
 * Time: 21:58
 */

namespace App\Api\Tilde;


/**
 * Class ResponseObject
 * @package App\Api\Tilde
 */
class ResponseObject
{
    /** @var int */
    private $responseStatus;

    /** @var string */
    private $requestId;

    /**
     * ResponseObject constructor.
     * @param string $json_response
     */
    public function __construct(string $json_response)
    {
        $json_decoded = json_decode($json_response);

        $this->responseStatus = $json_decoded->status;
        $this->requestId = $json_decoded->request_id;
    }
}
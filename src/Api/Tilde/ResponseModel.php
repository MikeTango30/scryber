<?php
/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-24
 * Time: 21:58
 */

namespace App\Api\Tilde;


/**
 * Class ResponseModel
 * @package App\Api\Tilde
 */
class ResponseModel
{
    const SUCCESS = 0;
    const NO_SPEECH = 1;
    const DECODING_ERROR = 2;
    const IN_QUEUE = 3;
    const PROCESSING = 4;
    const TYPE_NOT_RECOGNIZED = 5;
    const RECOGNITION_TIMEOUT = 6;
    const TRY_LATER = 9;
    const ERROR = 10;

    /** @var int */
    private $responseStatus;


    /** @var string */
    private $requestId;
    /**
     * ResponseModel constructor.
     * @param string $json_response
     */
    public function __construct(string $json_response)
    {
        $json_decoded = json_decode($json_response);

        $this->responseStatus = $json_decoded->status;
        $this->requestId = $json_decoded->request_id;
    }

    /**
     * @return int
     */
    public function getResponseStatus(): int
    {
        return $this->responseStatus;
    }

    /**
     * @return string
     */
    public function getResponseStatusText() : string
    {
        switch ($this->responseStatus) {
            case self::SUCCESS:
                return 'Success';
                break;

            case self::NO_SPEECH:
                return 'No speech';
                break;

            case self::DECODING_ERROR:
                return 'Decoding error';
                break;

            case self::IN_QUEUE:
                return 'In queue';
                break;

            case self::PROCESSING:
                return 'Processing';
                break;

            case self::TYPE_NOT_RECOGNIZED:
                return 'Type not recognized';
                break;

            case self::RECOGNITION_TIMEOUT:
                return 'Recognition timeout';
                break;

            case self::ERROR:
                return 'Error';
                break;
        }
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }


}
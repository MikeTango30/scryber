<?php
/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-24
 * Time: 23:09
 */

namespace App\Api\Tilde;


use GuzzleHttp\Client;

/**
 * Class Connector
 * @package App\Api\Tilde
 */
class Connector
{

    const SEND_FILE_URL = '/client/dynamic/recognize';

    /**
     * Connector constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param RequestObject $requestObject
     * @return ResponseObject
     */
    public function SendFile(RequestObject $requestObject): ResponseObject
    {
        try {
            return $this->call(self::SEND_FILE_URL, $requestObject);
        } catch (\GuzzleHttp\Exception\ConnectException $exception) {
            return false;
        }

        return false;
    }


    /**
     * @param $url
     * @param $requestObject
     * @return ResponseObject
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function call($url, $requestObject): ResponseObject
    {
        $client = new Client(['base_uri' => getenv(TILDE_URL)]);
        $response = $client->request(
            'POST',
            $url . getenv(TILDE_RECOGNITION_SYSTEM), [
                'multipart' => [
                    [
                        'name' => 'timestamp',
                        'contents' => $requestObject->getTimestamp()
                    ],
                    [
                        'name' => 'appID',
                        'contents' => $requestObject->getAppId()
                    ],
                    [
                        'name' => 'appKey',
                        'contents' => $requestObject->appKey()
                    ],
                    [
                        'name' => 'timestamp',
                        'contents' => $requestObject->getAppKey()
                    ],
                    [
                        'name' => 'audio',
                        'contents' => $requestObject->getAudioContent()
                    ],
                    [
                        'name' => 'mode',
                        'contents' => $requestObject->getMode()
                    ]
                ]
            ]
        );

        return new ResponseObject($response);
    }
}
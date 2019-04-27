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
    const STATUS_CHECK_URL = '/client/jobs/%s.status';
    const RESULTS_SUMMARY_URL = '/client/jobs/%s.summary';
    const RESULTS_TXT_URL = '/client/jobs/%s.transcribed.txt';
    const RESULTS_SRT_URL = '/client/jobs/%s.transcribed.srt';
    const RESULTS_CTM_URL = '/client/jobs/%s.ctm';

    /**
     * Connector constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param RequestModel $requestObject
     * @return ResponseModel
     */
    public function sendFile(RequestModel $requestObject): ResponseModel
    {
        try {
            return $this->callPost(self::SEND_FILE_URL, $requestObject);
        } catch (\GuzzleHttp\Exception\ConnectException $exception) {
            return false;
        }

        return false;
    }

    /**
     * @param string $jobId
     * @return ResponseModel
     */
    public function checkJobStatus(string $jobId) : ResponseModel
    {
        $url = sprintf(self::STATUS_CHECK_URL, $jobId);
        try {
            return new ResponseModel($this->callGet($url));
        } catch (\GuzzleHttp\Exception\ConnectException $exception) {
            return false;
        }

        return false;
    }

    /**
     * @param string $jobId
     * @return SummaryModel
     */
    public function getJobSummary(string $jobId) : SummaryModel
    {
        $url = sprintf(self::RESULTS_SUMMARY_URL, $jobId);
        try {
            return new SummaryModel($this->callGet($url));
        } catch (\GuzzleHttp\Exception\ConnectException $exception) {
            return false;
        }

        return false;
    }

    /**
     * @param string $jobId
     * @return string
     */
    public function getScrybedTxt(string $jobId) : string
    {
        $url = sprintf(self::RESULTS_TXT_URL, $jobId);
        try {
            return $this->callGet($url);
        } catch (\GuzzleHttp\Exception\ConnectException $exception) {
            return false;
        }

        return '';
    }

    public function getScrybedCtm(string $jobId) : CtmModel
    {
        $url = sprintf(self::RESULTS_CTM_URL, $jobId);
        try {
            return new CtmModel($this->callGet($url));
        } catch (\GuzzleHttp\Exception\ConnectException $exception) {
            return false;
        }

        return false;
    }

    /**
     * @param $url
     * @param $requestObject
     * @return ResponseModel
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function callPost($url, RequestModel $requestObject): ResponseModel
    {
        $client = new Client(['base_uri' => $_ENV['TILDE_URL'] ]);
        $response = $client->request(
            'POST',
            $url,
            [
                'query' => ['system' => $_ENV['TILDE_RECOGNITION_SYSTEM'] ],
                'multipart' => [
                    [
                        'name' => 'timestamp',
                        'contents' => $requestObject->getTimestamp()->format("U")
                    ],
                    [
                        'name' => 'appID',
                        'contents' => $requestObject->getAppId()
                    ],
                    [
                        'name' => 'appKey',
                        'contents' => $requestObject->getAppKey()
                    ],
                    [
                        'name' => 'audio',
                        'contents' => file_get_contents($requestObject->getAudioFilePath())
                    ],
                    [
                        'name' => 'mode',
                        'contents' => $requestObject->getMode()
                    ]
                ]
            ]
        );

        return new ResponseModel($response->getBody());
    }

    /**
     * @param $url
     * @param array $callParams
     * @return ResponseModel
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function callGet($url, array $callParams = []) : string
    {
        $client = new Client(['base_uri' => $_ENV['TILDE_URL'] ]);
        $response = $client->request(
            'GET',
            $url,
            [
                'query' => $callParams,

            ]
        );

        return $response->getBody();
    }
}
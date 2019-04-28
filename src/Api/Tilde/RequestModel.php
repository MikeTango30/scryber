<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-24
 * Time: 21:58
 */

namespace App\Api\Tilde;


class RequestModel
{

    const REQUEST_MODE_SKIP_SEGMENTATION_DIARIZATION = 'skip_diariz';
    const REQUEST_MODE_SPEAKERS = 'speakers';
    const REQUEST_MODE_CTM = 'ctm';
    const REQUEST_MODE_FULL = 'full';
    const REQUEST_MODE_SKIP_POSTPROCESS = 'skip_postprocess';

    /** @var string */
    private $audioFilePath;

    /** @var string */
    private $mode;

    /** @var \DateTime */
    protected $timestamp;

    /** @var  string */
    private $callback;

    /** @var CallbackType */
    private $callback_type;

    /** @var string */
    private $appId;

    /** @var string */
    private $appSecret;

    /** @var string */
    private $appKey;

    public function __construct(string $audioFilePath)
    {
        $this->timestamp = new \DateTime();
        $this->audioFilePath = $audioFilePath;
        $this->mode = self::REQUEST_MODE_CTM;
        $this->appId = $_ENV['TILDE_APP_ID'];
        $this->appSecret = $_ENV['TILDE_APP_SECRET'];
    }

    /**
     * @return string
     */
    public function getAudioFilePath(): string
    {
        return $this->audioFilePath;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp(\DateTime $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param string $callback
     */
    public function setCallback(string $callback): void
    {
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getCallback(): string
    {
        return $this->callback;
    }


        /**
     * @param CallbackType $callback_type
     */
    public function setCallbackType(CallbackType $callback_type): void
    {
        $this->callback_type = $callback_type;
    }

    /**
     * @return CallbackType
     */
    public function getCallbackType(): CallbackType
    {
        return $this->callback_type;
    }


    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }


    /**
     * @return string
     */
    public function getAppKey(): string
    {
        $this->appKey = sha1($this->timestamp->getTimestamp().$this->appId.$this->appSecret);
        return $this->appKey;
    }

}
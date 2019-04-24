<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-24
 * Time: 21:58
 */

namespace App\Api\Tilde;


class RequestObject
{
    /** @var string */
    private $audioContent;

    /** @var RequestMode */
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

    public function __construct(string $audioBlob)
    {
        $this->timestamp = time();
        $this->audioContent = $audioBlob;
        $this->mode = RequestMode::CTM;
        $this->appId = getenv('TILDE_APP_ID');
        $this->appSecret = getenv('TILDE_APP_SECRET');
    }

    /**
     * @return string
     */
    public function getAudioContent(): string
    {
        return $this->audioContent;
    }

    /**
     * @return RequestMode
     */
    public function getMode(): RequestMode
    {
        return $this->mode;
    }

    /**
     * @param RequestMode $mode
     */
    public function setMode(RequestMode $mode): void
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
        $this->appKey = sha1($this->timestamp.$this->appId.$this->appSecret);
    }

}
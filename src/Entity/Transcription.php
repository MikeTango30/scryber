<?php

namespace App\Entity;

class Transcription
{
    /**
     * @var float
     */
    private $beginTime;

    /**
     * @var float
     */
    private $endTime;

    /**
     * @var string
     */
    private $wordId;

    /**
     * @var float
     */
    private $confidence;

    /**
     * @var string
     */
    private $word;

    /**
     * Transcription constructor.
     * @param $beginTime
     * @param $endTime
     * @param $wordId
     * @param $confidence
     * @param $word
     */
    public function __construct($beginTime, $endTime, $wordId, $confidence, $word)
    {
        $this->beginTime = $beginTime;
        $this->endTime = $endTime;
        $this->wordId = $wordId;
        $this->confidence = $confidence;
        $this->word = $word;
    }

    /**
     * @return float
     */
    public function getBeginTime(): float
    {
        return $this->beginTime;
    }

    /**
     * @return float
     */
    public function getEndTime(): float
    {
        return $this->endTime;
    }

    /**
     * @return string
     */
    public function getWordId(): string
    {
        return $this->wordId;
    }

    /**
     * @return float
     */
    public function getConfidence(): float
    {
        return $this->confidence;
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }
}
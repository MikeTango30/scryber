<?php

namespace App\Model;

class TranscriptionLine
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
     * @var float
     */
    private $duration;

    /**
     * @var float
     */
    private $confidence;

    /**
     * @var string
     */
    private $word;


    public function __construct($beginTime, $endTime, $duration, $confidence, $word)
    {
        $this->beginTime = $beginTime;
        $this->endTime = $endTime;
        $this->duration = $duration;
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
     * @param float $beginTime
     */
    public function setBeginTime(float $beginTime): void
    {
        $this->beginTime = $beginTime;
    }

    /**
     * @return float
     */
    public function getEndTime(): float
    {
        return round($this->endTime,2);
    }

    /**
     * @param float $endTime
     */
    public function setEndTime(float $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * @param float $duration
     */
    public function setDuration(float $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return float
     */
    public function getConfidence(): float
    {
        return $this->confidence;
    }

    /**
     * @param float $confidence
     */
    public function setConfidence(float $confidence): void
    {
        $this->confidence = $confidence;
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * @param string $word
     */
    public function setWord(string $word): void
    {
        $this->word = $word;
    }

    public function getArray()
    {
        $lineArray = [];
        $lineArray['beginTime'] = $this->getBeginTime();
        $lineArray['endTime'] = $this->getEndTime();
        $lineArray['duration'] = $this->getDuration();
        $lineArray['confidence'] = $this->getConfidence();
        $lineArray['word'] = $this->getWord();

        return $lineArray;
    }
}
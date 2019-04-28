<?php


namespace App\Api\Tilde;


class CtmLine
{
    /** @var string */
    protected $utterance;

    /** @var int */
    protected $channel;

    /** @var float */
    protected $beginTime;

    /** @var float */
    protected $duration;

    /** @var string */
    protected $word;

    /** @var float */
    protected $confidence;

    public function __construct(string $utterance, int $channel, string $beginTime, float $duration, string $word, float $confidence)
    {
        $this->utterance = $utterance;
        $this->channel = $channel;
        $this->beginTime = $beginTime;
        $this->duration = $duration;
        $this->word = $word;
        $this->confidence = $confidence;

    }

    /**
     * @return string
     */
    public function getUtterance(): string
    {
        return $this->utterance;
    }

    /**
     * @return int
     */
    public function getChannel(): int
    {
        return $this->channel;
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
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getWordId(): string
    {
        return $this->word;
    }

    /**
     * @return float
     */
    public function getConfidence(): float
    {
        return $this->confidence;
    }
}
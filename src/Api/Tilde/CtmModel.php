<?php
/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-27
 * Time: 20:37
 */

namespace App\Api\Tilde;


class CtmLine
{
    /** @var string */
    protected $uttercance;

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

    public function __construct(string $uttercance, int $channel, string $beginTime, float $duration, string $word, float $confidence)
    {
        $this->uttercance = $uttercance;
        $this->channel = $channel;
        $this->beginTime = $beginTime;
        $this->duration = $duration;
        $this->word = $word;
        $this->confidence = $confidence;

    }

    /**
     * @return string
     */
    public function getUttercance(): string
    {
        return $this->uttercance;
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
     * @return int
     */
    public function getWordId(): int
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

class CtmModel
{
    /** @var array */
    private $ctm;

    public function __construct(string $ctmRawContents)
    {
        $ctmArray = [];
        $ctmContentLines = explode("\n", $ctmRawContents);
        if(!empty($ctmContentLines)) {
            foreach ($ctmContentLines as $ctmContentLine) {
                if(!empty($ctmContentLine)) {
                    list($uttercance, $channel, $beginTime, $duration, $word, $confidence) = explode(' ', $ctmContentLine);
                    $ctmArray[] = new CtmLine($uttercance, $channel, $beginTime, $duration, $word, $confidence);
                }
            }
        }

        $this->ctm = $ctmArray;
    }
}
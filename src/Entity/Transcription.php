<?php


namespace App\Entity;


class Transcription
{
    private $wordStart;
    private $wordEnd;
    private $wordId;
    private $confidenceScore;

    /**
     * Transcription constructor.
     * @param $wordStart
     * @param $wordEnd
     * @param $wordId
     * @param $confidenceScore
     */
    public function __construct($wordStart, $wordEnd, $wordId, $confidenceScore)
    {
        $this->wordStart = $wordStart;
        $this->wordEnd = $wordEnd;
        $this->wordId = $wordId;
        $this->confidenceScore = $confidenceScore;
    }

    /**
     * @return mixed
     */
    public function getWordStart()
    {
        return $this->wordStart;
    }

    /**
     * @return mixed
     */
    public function getWordEnd()
    {
        return $this->wordEnd;
    }

    /**
     * @return mixed
     */
    public function getWordId()
    {
        return $this->wordId;
    }

    /**
     * @return mixed
     */
    public function getConfidenceScore()
    {
        return $this->confidenceScore;
    }
}
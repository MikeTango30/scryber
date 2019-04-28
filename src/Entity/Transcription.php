<?php

namespace App\Entity;

class Transcription
{
    private $wordStart;
    private $wordEnd;
    private $wordId;
    private $confidenceScore;
    private $word;

    /**
     * Transcription constructor.
     * @string $wordStart
     * @string $wordEnd
     * @string $wordId
     * @string $confidenceScore
     * @string $word
     */
    public function __construct(
        string $wordStart,
        string $wordEnd,
        string $wordId,
        string $confidenceScore,
        string $word
    ){
        $this->wordStart = $wordStart;
        $this->wordEnd = $wordEnd;
        $this->wordId = $wordId;
        $this->confidenceScore = $confidenceScore;
        $this->word = $word;
    }

    /**
     * @return string
     */
    public function getWordStart(): string
    {
        return $this->wordStart;
    }

    /**
     * @return string
     */
    public function getWordEnd(): string
    {
        return $this->wordEnd;
    }

    /**
     * @return string
     */
    public function getWordId(): string
    {
        return $this->wordId;
    }

    /**
     * @return string
     */
    public function getConfidenceScore(): string
    {
        return $this->confidenceScore;
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }

}
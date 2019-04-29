<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-27
 * Time: 20:17
 */

namespace App\Api\Tilde;


class SummaryModel
{
    //{"date": "1556383434.2628634", "confidence": 0.784, "words": 11, "speakers": ["R1"], "length": 7.957375}
    /** @var \DateTime */
    private $date;

    /** @var float */
    private $confidence;

    /** @var int */
    private $words;

    /** @var [string] */
    private $speakers;

    /** @var float */
    private $length;

    public function __construct(string $jsonString)
    {
        $json_decoded = json_decode($jsonString);

        $this->date = \DateTime::createFromFormat('U', $json_decoded->date);
        $this->confidence = $json_decoded->confidence;
        $this->words = $json_decoded->words;
        $this->speakers = $json_decoded->speakers;
        $this->length = $json_decoded->length;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return float
     */
    public function getConfidence(): float
    {
        return $this->confidence;
    }

    /**
     * @return int
     */
    public function getWords(): int
    {
        return $this->words;
    }

    /**
     * @return mixed
     */
    public function getSpeakers()
    {
        return $this->speakers;
    }

    /**
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * @return float
     */
    public function getLengthRounded(): float
    {
        return round($this->length,2);
    }



}
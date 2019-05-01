<?php


namespace App\Entity;


class SpanBlock
{
    /**
     * @var float
     */
    protected $dataWordStart;

    /**
     * @var float
     */
    protected $dataWordEnd;

    /**
     * @var float
     */
    protected $dataWordConfidence;

    /**
     * @var string
     */
    protected $word;

    /**
     * SpanAttributes constructor.
     * @param float $dataWordStart
     * @param float $dataWordEnd
     * @param float $dataWordConfidence
     * @param string $word
     */
    public function __construct(float $dataWordStart, float $dataWordEnd, float $dataWordConfidence, string $word)
    {
        $this->dataWordStart = "data-word-start"."='".$dataWordStart."'";
        $this->dataWordEnd = "data-word-end"."='".$dataWordEnd."'";
        $this->dataWordConfidence = "data-word-conf"."='" .$dataWordConfidence."'";
        $this->word = $word;
    }

    public function glueSpan()
    {
        $spanBlock =
            "<span"." ".
            $this->dataWordStart." ".
            $this->dataWordEnd." ".
            $this->dataWordConfidence.
            ">".
            $this->word.
            "</span>";

        return $spanBlock;
    }
}
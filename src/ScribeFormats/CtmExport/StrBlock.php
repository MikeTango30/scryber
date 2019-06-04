<?php


namespace App\ScribeFormats\CtmExport;


class StrBlock
{
    /** @var int */
    private $sequenceNo;

    /** @var \DateInterval */
    private $beginTime;

    /** @var \DateInterval */
    private $endTime;

    /** @var string */
    private $text;

    /**
     * @return int
     */
    public function getSequenceNo(): int
    {
        return $this->sequenceNo;
    }

    /**
     * @param int $sequenceNo
     */
    public function setSequenceNo(int $sequenceNo): void
    {
        $this->sequenceNo = $sequenceNo;
    }

    /**
     * @return \DateInterval
     */
    public function getBeginTime(): \DateInterval
    {
        return $this->beginTime;
    }

    /**
     * @param string
     */
    public function setBeginTime(string $beginTime): void
    {
        $this->beginTime = $this->getSubtitleTimeObject($beginTime);
    }

    /**
     * @return \DateInterval
     */
    public function getEndTime(): \DateInterval
    {
        return $this->endTime;
    }

    /**
     * @param string $endTime
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $this->getSubtitleTimeObject($endTime);
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param string $text
     */
    public function appendText(string $text) : void
    {
        $this->text .= $text;
    }

    public function getSubtitleBlock() : string
    {
        $response = '';

        $response = $this->getSequenceNo();
        $response .= PHP_EOL;
        $response .= sprintf("%s --> %s".PHP_EOL, $this->getSubtitleTime($this->getBeginTime()), $this->getSubtitleTime($this->getEndTime()));
        $response .= $this->getText().PHP_EOL.PHP_EOL;

        return $response;
    }

    private function getSubtitleTime(\DateInterval $time) : string
    {
        $timeObject = $time;

        return sprintf("%02d:%02d:%02d,%02d", $timeObject->h, $timeObject->i, $timeObject->s, (int)$timeObject->f);
    }

    private function getSubtitleTimeObject(string $time) : \DateInterval
    {
        $response = new \DateInterval(sprintf("PT%dS", $time));

        return $response;
    }
}

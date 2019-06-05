<?php


namespace App\ScribeFormats;


use App\Api\Tilde\CtmLine;
use App\Api\Tilde\CtmModel;
use App\Entity\File;
use App\Model\TranscriptionLine;

class CtmTransformer
{

    private $lastTime;
    private $lastDuration;
    private $lastUtterance;
    private $lastUtteranceBegin;

    public function __construct()
    {
        $this->lastTime = 0;
        $this->lastDuration = 0;
        $this->lastUtterance = null;
        $this->lastUtteranceBegin = 0;
    }

    /**
     * @param File $file
     * @return array
     */
    public function getCtmJson(File $file) : array
    {
        $ctm = new CtmModel($file->getDefaultCtm());
        $text = $file->getPlainText();
        $text = trim(preg_replace('/\s+/', ' ', $text)); // get rid of new lines
        $words = explode(' ', $text);
        $wordsCount = $file->getWordsCount();

        if(count($words)!==$wordsCount)
            return new \Exception("Unable to format JSON from CTM source", 1);

        $jsonObject = [];

        $index = 0;
        $currentDec = 0;
        /** @var CtmLine $_ctm */
        foreach ($ctm->getCtm() as $_ctm) {
            $beginTime = $this->getUpdatedTime($_ctm->getBeginTime(), $_ctm->getDuration(), $_ctm->getUtterance());
            $endTime = $beginTime + $_ctm->getDuration();
            $transcriptionLine = new TranscriptionLine(
                $beginTime,
                $endTime,
                $_ctm->getDuration(),
                $_ctm->getConfidence(),
                $words[$index]
            );

            array_push($jsonObject, $transcriptionLine->getArray());

            $index++;
        }

        return $jsonObject;
    }

    private function getUpdatedTime(float $currentTime, float $currentDuration, string $utterance) : float
    {
        $updatedTime = 0.0;
        if ($this->lastUtterance != $utterance) {
            $this->lastUtterance = $utterance;
            $this->lastUtteranceBegin += $this->lastTime + $this->lastDuration;
        }
        $updatedTime = $currentTime + $this->lastUtteranceBegin;
        $this->lastTime = $currentTime + $currentDuration;
        $this->lastDuration = $currentDuration;

        return $updatedTime;
    }
}
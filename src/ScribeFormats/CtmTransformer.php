<?php


namespace App\ScribeFormats;


use App\Api\Tilde\CtmLine;
use App\Api\Tilde\CtmModel;
use App\Entity\File;
use App\Model\TranscriptionLine;

class CtmTransformer
{

    private $currentDec;
    private $lastUtterance;

    public function __construct()
    {
        $this->currentDec = 0;
        $this->lastUtterance = null;
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
            $beginTime = $this->getUpdatedTime($_ctm->getBeginTime(), $_ctm->getUtterance());
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

    private function getUpdatedTime(float $time, string $utterance) : float
    {
        $updatedTime = 0.0;
        if (!isset($this->lastUtterance)) {
            $this->lastUtterance = $utterance;
        }
        if ($this->lastUtterance != $utterance) {
            $this->lastUtterance = $utterance;
            $this->currentDec++;
        }
        $updatedTime = $time + ($this->currentDec * 10);

        return $updatedTime;
    }
}
<?php


namespace App\ScribeFormats;


use App\Api\Tilde\CtmLine;
use App\Api\Tilde\CtmModel;
use App\Entity\File;
use App\Model\TranscriptionLine;

class CtmTransformer
{
    /**
     * @param File $file
     * @return array
     */
    public function getCtmJson(File $file): array
    {
        $ctm = new CtmModel($file->getDefaultCtm());
        $text = $file->getPlainText();
        $text = trim(preg_replace('/\s+/', ' ', $text)); // get rid of new lines
        $words = explode(' ', $text);
        $wordsCount = $file->getWordsCount();

        if (count($words) !== $wordsCount)
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

    /**
     * @param float $currentTime
     * @param string $utterance
     * @return float
     */
    private function getUpdatedTime(float $currentTime, string $utterance): float
    {
        $updatedTime = 0.0;
        $updatedTime = $currentTime + $this->extractBeginTimeFromUtterance($utterance);

        return $updatedTime;
    }

    /**
     * @param string $utterance
     * @return array|float|string
     */
    private function extractBeginTimeFromUtterance(string $utterance)
    {
        $beginTime = 0.0;
        list($partNo, , $beginTime, $endTime) = preg_split("/[-_]/", $utterance);

        return $beginTime;

    }
}
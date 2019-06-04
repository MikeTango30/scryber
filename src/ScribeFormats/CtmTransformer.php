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
        /** @var CtmLine $_ctm */
        foreach ($ctm->getCtm() as $_ctm) {
            $transcriptionLine = new TranscriptionLine(
                $_ctm->getBeginTime(),
                $_ctm->getBeginTime()+$_ctm->getDuration(),
                $_ctm->getDuration(),
                $_ctm->getConfidence(),
                $words[$index]
            );

            array_push($jsonObject, $transcriptionLine->getArray());

            $index++;
        }

        return $jsonObject;
    }
}
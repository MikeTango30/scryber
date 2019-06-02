<?php


namespace App\ScribeFormats;


use App\Api\Tilde\CtmLine;
use App\Api\Tilde\CtmModel;
use App\Entity\File;

class CtmTransformer
{
    /**
     * @param File $file
     * @return array
     */
    public function getCtmJson(File $file) : array
    {
        $ctm = new CtmModel($file->getFileDefaultCtm());
        $text = $file->getFileTxt();
        $text = trim(preg_replace('/\s+/', ' ', $text )); // get rid of new lines
        $words = explode(' ', $text);
        $wordsCount = $file->getFileWords();

        if(count($words)!==$wordsCount)
            return new \Exception("Unable to format JSON from CTM source", 1);

        $jsonObject = [];

        $index = 0;
        /** @var CtmLine $_ctm */
        foreach ($ctm->getCtm() as $_ctm) {
            $jsonLine = new \stdClass();
            $jsonLine->duration = $_ctm->getDuration();
            $jsonLine->beginTime = $_ctm->getBeginTime();
            $jsonLine->endTime = $_ctm->getBeginTime()+$_ctm->getDuration();
            $jsonLine->confidence = $_ctm->getConfidence();
            $jsonLine->word = $words[$index];

            array_push($jsonObject, $jsonLine);

            $index++;
        }

        return $jsonObject;
    }
}
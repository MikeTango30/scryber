<?php

namespace App\Repository;
use App\Api\Tilde\Connector;
use App\Entity\Transcription;
use App\Api\Tilde\CtmLine;

class TranscriptionAggregator
{
    /**
     * @return Transcription []
     */
    public function prepareData(string $jobId)
    {
        $connector = new Connector();
        $text = $connector->getScrybedTxt($jobId);
        $ctm = $connector->getScrybedCtm($jobId)->getCtm();

        $text = trim(preg_replace('/\s+/', ' ', $text )); // get rid of new lines
        $textParts = explode(" ", $text);

        /**
         * create obj array
         */
        $i=0;
        $transcription = [];
        foreach ($ctm as $ctmLine) {
            $transcription [] = new Transcription(
                $ctmLine->getBeginTime(),
                $ctmLine->getBeginTime() + $ctmLine->getDuration(),
                $ctmLine->getWordId(),
                $ctmLine->getConfidence(),
                $textParts[$i]
            );
            $i++;
        }

        return $transcription;
    }
}
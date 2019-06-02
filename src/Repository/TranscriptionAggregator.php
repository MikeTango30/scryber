<?php

namespace App\Repository;
use App\Api\Tilde\Connector;
use App\Api\Tilde\CtmLine;
use App\Model\TranscriptionLine;

class TranscriptionAggregator
{
    /**
     * @return TranscriptionLine []
     */
    public function prepareData(string $jobId)
    {
        $connector = new Connector();
        if ($jobId) {
            $text = $connector->getScrybedTxt($jobId);
            $ctm = $connector->getScrybedCtm($jobId)->getCtm();
        }
        else {
            $text = '';
            $ctm = [];
        }

        $text = trim(preg_replace('/\s+/', ' ', $text )); // get rid of new lines
        $textParts = explode(" ", $text);

        /**
         * create obj array
         */
        $i=0;
        $transcription = [];
        /** @var CtmLine $ctmLine */
        foreach ($ctm as $ctmLine) {
            $transcription [] = new TranscriptionLine(
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
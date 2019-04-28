<?php

namespace App\Repository;
use App\Entity\Transcription;

class TranscriptionAggregator
{
    /**
     * @return Transcription []
     */
    public function prepareData()
    {
        /**
         * create CTM file parts array
         */
        $transcribedCtm = file_get_contents(__DIR__."/../../assets/transcribed.ctm");
        $transcribedCtm = explode("\n", $transcribedCtm);

        $transcribedCtmParts = [];
        foreach ($transcribedCtm as $part) {
            $transcribedCtmParts [] = explode(" ", $part);
        }

        /**
         * create TXT file parts array
         */
        $transcribedTxt = file_get_contents(__DIR__."/../../assets/transcribed.txt");
        $transcribedTxt = trim(preg_replace('/\s+/', ' ', $transcribedTxt )); // get rid of new lines
        $transcribedTxtParts = explode(" ", $transcribedTxt);


        /**
         * append parts from TXT to parts from CTM
         */
        for ($i = 0; $i < sizeof($transcribedCtmParts); $i++) {
            array_push($transcribedCtmParts[$i], $transcribedTxtParts[$i]);
        }

        /**
         * create obj array
         */
        $transcription = [];
        foreach ($transcribedCtmParts as $transcribedCtmPart) {
            $transcription [] = new Transcription(
                $transcribedCtmPart[2], //word start
                $transcribedCtmPart[3], //word end
                $transcribedCtmPart[4], //word id
                $transcribedCtmPart[5], //confidence rating
                $transcribedCtmPart[6]  //word
            );
        }

        return $transcription;
    }
}
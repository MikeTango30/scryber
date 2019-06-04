<?php

namespace App\Repository;
use App\Api\Tilde\Connector;
use App\Api\Tilde\CtmLine;
use App\Model\Transcription;
use App\Model\TranscriptionLine;

class TranscriptionAggregator
{
//    /**
//     * @return TranscriptionLine []
//     */
//    public function prepareData(string $jobId)
//    {
//        $connector = new Connector();
//        if ($jobId) {
//            $text = $connector->getScrybedTxt($jobId);
//            $ctm = $connector->getScrybedCtm($jobId)->getCtm();
//        }
//        else {
//            $text = '';
//            $ctm = [];
//        }
//
//        $text = trim(preg_replace('/\s+/', ' ', $text )); // get rid of new lines
//        $textParts = explode(" ", $text);
//
//        /**
//         * create obj array
//         */
//        $i=0;
//        $transcription = [];
//        /** @var CtmLine $ctmLine */
//        foreach ($ctm as $ctmLine) {
//            $transcription [] = new TranscriptionLine(
//                $ctmLine->getBeginTime(),
//                $ctmLine->getBeginTime() + $ctmLine->getDuration(),
//                $ctmLine->getWordId(),
//                $ctmLine->getConfidence(),
//                $textParts[$i]
//            );
//            $i++;
//        }
//
//        return $transcription;
//    }

    /**
     * @param string $text
     * @return Transcription
     */
    public function aggregateTranscriptionJsonForSaving(string $text): array
    {
        $textArray = [];
        $textAttributes = [];
        $transcriptionLines = [];
        $jsonObject = [];

        $textStrippedNewLines = trim(str_replace(array("\r", "\n", "&nbsp;"), '', $text));
        $spans = preg_split('/  +/', $textStrippedNewLines);

        foreach ($spans as $span) {
            $spanAttributes = trim(str_replace(array('<span class=""', '</span>'), '', $span));
            $spanAttributes = substr($spanAttributes, 0, strpos($spanAttributes, ">"));
            $spanAttributes = explode(' ', $spanAttributes);

            foreach ($spanAttributes as $spanAttribute) {
                $tmp = explode( '=', $spanAttribute );
                $textAttributes[ $tmp[0] ] = str_replace('"','', $tmp[1]);
            }
            $textArray[] = $textAttributes;
        }

        foreach ($textArray as $textLine) {
            $transcriptionLine = new TranscriptionLine(
                floatval($textLine['data-word-start']),
                floatval($textLine['data-word-end']),
                floatval($textLine['data-word-end']) - floatval($textLine['data-word-start']),
                floatval($textLine['data-word-conf']),
                $textLine['data-word-content']
            );
            $transcriptionLines[] = $transcriptionLine;
            array_push($jsonObject, $transcriptionLine->getArray());
        }

        return $jsonObject;
    }
}
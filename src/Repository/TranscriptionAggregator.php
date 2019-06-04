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
    public function aggregateTranscriptionJsonForSaving(string $textSpans): array
    {
        $textArray = [];
        $textAttributes = [];
        $transcriptionLines = [];
        $jsonObject = [];
        $i = 0;

        $textStrippedNewLinesAndClasses = trim(str_replace(array("\r", "\n", "no-word", "sync", "highlight", "word"), '', $textSpans));
        $spans = explode('</span>', $textStrippedNewLinesAndClasses);

        $textStripped = preg_split('/  +/', strip_tags($textSpans));
        $textStripped = str_replace(array("\r", "\n"), '', $textStripped);

        foreach ($spans as $span) {
            if (strpos($span, 'empty') == false) {
                if (!empty($span)) {
                    $spanAttributes = trim(str_replace(array('<span class=""'), '', $span));
                    $spanAttributes = substr($spanAttributes, 0, strpos($spanAttributes, ">"));
                    $spanAttributes = strstr($spanAttributes, 'data--start=');
                    $spanAttributes = explode(' ', $spanAttributes);

                    foreach ($spanAttributes as $spanAttribute) {

                        $tmp = explode('=', $spanAttribute);
                        $textAttributes[$tmp[0]] = str_replace('"', '', $tmp[1]);
                        if ($i >= count($textStripped)) {
                            $textAttributes['word'] = '';
                        } elseif ($i <count($textStripped)) {
                            $textAttributes['word'] = trim($textStripped[$i], " ");
                        }
                    }
                    $textArray[] = $textAttributes;
                    $i++;
                }
            }
        }

        foreach ($textArray as $textLine) {
            $transcriptionLine = new TranscriptionLine(
                floatval($textLine['data--start']),
                floatval($textLine['data--end']),
                floatval($textLine['data--end']) - floatval($textLine['data--start']),
                floatval($textLine['data--conf']),
                $textLine['word']
            );
            $transcriptionLines[] = $transcriptionLine;
            array_push($jsonObject, $transcriptionLine->getArray());
        }

        return $jsonObject;
    }
}
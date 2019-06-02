<?php


namespace App\Repository;

use App\Model\SpanBlock;
use App\Model\Transcription;
use App\Model\TranscriptionLine;

class TextGenerator
{
    /**
     * @param Transcription $transcription
     * @return array
     */
    public function generateSpans(Transcription $transcription): array
    {

        $spanTags = [];
        $transcriptionElement = $transcription->getTranscriptionLines();
        /** @var TranscriptionLine $element */
        foreach ($transcriptionElement as $element) {
            $spanBlock = new SpanBlock(
                $element->getBeginTime(),
                $element->getEndTime(),
                $element->getConfidence(),
                $element->getWord()
            );
            $spanTags [] = $spanBlock->glueSpan();
        }

        return $spanTags;
    }
}
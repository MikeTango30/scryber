<?php


namespace App\Repository;

use App\Entity\SpanBlock;

class TextGenerator
{
    /**
     * @param array $transcription
     * @return array $spanTags
     */

    public function generateSpans(array $transcription): array
    {

        $spanTags = [];
        foreach ($transcription as $element) {
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
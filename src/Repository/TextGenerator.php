<?php


namespace App\Repository;

class TextGenerator
{
    /**
     * @param array $transcription
     * @return array html parts
     */
    //reikia generuoti ne spanu string masyva, o span objektu masyva su symfony komponentu

    public function generateHtml(array $transcription)
    {

        $spanDataParts = [];
        $words = [];
        foreach ($transcription as $element) {
            $spanDataParts [] = [
                "data-word-start" . "='" . $element->getWordStart() . "'",
                "data-word-end" . "='" . $element->getWordEnd() . "'",
                "data-word-conf" . "='" . $element->getConfidenceScore() . "'"
            ];
            $words [] = $element->getWord();
        }

        $spanDataAttributes = [];
        foreach ($spanDataParts as $spanDataPart) {
            $spanDataAttributes [] = implode(" ", $spanDataPart);
        }

        /**
         * generate html div and span blocks
         * 1st foreach generates html for blocks part until $word
         * 2nd - generates $word and closing tags
         */

        $htmlSpanDataAttributes = [];
        foreach ($spanDataAttributes as $spanDataAttribute) {
            $htmlSpanDataAttributes [] = <<<HTML
            <div style="display:inline" contenteditable="true">
                <span $spanDataAttribute>
HTML;
        }
        $htmlWords = [];
        foreach ($words as $word) {
            $htmlWords [] = <<<HTML
                      $word
                 </span>
             </div>   
HTML;
        }

        /**
         * append $htmlWord to htmlSpanDataAttribute
         */
        $htmlTranscriptionWordBlocks = [];
        foreach ($htmlSpanDataAttributes as $key => $htmlSpanDataAttribute) {
            $htmlWord = $htmlWords[$key];
            $htmlTranscriptionWordBlocks[$key] = $htmlSpanDataAttribute.$htmlWord;
        }

        return $htmlTranscriptionWordBlocks;

    }
}
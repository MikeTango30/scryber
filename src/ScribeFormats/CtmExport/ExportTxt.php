<?php


namespace App\ScribeFormats\CtmExport;


use App\Model\Transcription;
use App\Model\TranscriptionLine;
use App\ScribeFormats\CtmExport;

class ExportTxt implements CtmExport
{

    public function getExportContent(Transcription $textArray): string
    {
        $outputContents = [];

        /** @var TranscriptionLine $line */
        foreach ($textArray->getTranscriptionLines() as $line) {
            $outputContents[] = $line->getWord();
        }

        return implode(' ', $outputContents);
    }
}
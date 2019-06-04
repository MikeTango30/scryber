<?php


namespace App\ScribeFormats\CtmExport;


use App\Model\Transcription;
use App\Model\TranscriptionLine;
use App\ScribeFormats\CtmExport;

class ExportSrt implements CtmExport
{
    const SILECE_TIME_TO_SEPARATE_BLOCKS = 5.0;

    const LINE_LENGTH_LIMIT = 37;

    public function getExportContent(Transcription $textArray): string
    {
        $output_srt = '';

        $subBlockIndex = 1;
        $subBlockIsOpen = false;
        $subBlockLine = '';
        $subBlockLines = 1;
        $lastEndTime = 0.0;

        /** @var TranscriptionLine $line */
        foreach ($textArray->getTranscriptionLines() as $line) {
            /** @var StrBlock $subBlock */
            if ($line->getBeginTime() - $lastEndTime > self::SILECE_TIME_TO_SEPARATE_BLOCKS) {
                $subBlockIsOpen = false;
            }

            if ($subBlockIsOpen &&
                strlen($line->getWord()) > 2 &&
                //in_array(substr($line->getWord(), -1), array('.', ',', '!', '?', ';')) &&
                strlen($subBlockLine) + strlen($line->getWord()) >= self::LINE_LENGTH_LIMIT

            ) {
                if ($subBlockLines == 1) {
                    $subBlock->appendText(PHP_EOL);
                    $subBlockLines++;
                    $subBlockLine = '';
                } else {
                    $subBlockIsOpen = false;
                }
            }

            if ($subBlockIsOpen == false) {
                if (isset($subBlock)) {
                    $output_srt .= $subBlock->getSubtitleBlock();
                }
                $subBlock = new StrBlock();
                $subBlock->setSequenceNo($subBlockIndex);
                $subBlockIndex++;
                $subBlockLines = 1;
                $subBlockLine = '';
                $subBlockIsOpen = true;
                $subBlock->setBeginTime($line->getBeginTime());
            }

            $subBlock->appendText(empty($subBlockLine) ? $line->getWord() : ' '.$line->getWord());
            $subBlock->setEndTime($line->getEndTime());
            $subBlockLine .= empty($subBlockLine) ? $line->getWord() : ' '.$line->getWord();
            $lastEndTime = $line->getEndTime();

        }

        if (isset($subBlock)) {
            $output_srt .= $subBlock->getSubtitleBlock();
        }


        return $output_srt;
    }
}
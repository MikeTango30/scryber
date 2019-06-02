<?php


namespace App\Model;

class Transcription
{
    /** @var array */
    private $transcriptionLines;

    public function __construct(?array $textArray)
    {
        foreach ($textArray as $line) {
            $transcriptionLine = new TranscriptionLine(
                $line['beginTime'],
                $line['endTime'],
                $line['duration'],
                $line['confidence'],
                $line['word']
            );
            $this->transcriptionLines[] = $transcriptionLine;
        }
    }

    /**
     * @return array
     */
    public function getTranscriptionLines(): array
    {
        return $this->transcriptionLines;
    }

    /**
     * @param array $transcriptionLines
     */
    public function setTranscriptionLines(array $transcriptionLines): void
    {
        $this->transcriptionLines = $transcriptionLines;
    }

}

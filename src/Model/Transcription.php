<?php


namespace App\Model;

class Transcription
{
    /** @var array */
    private $transcriptionLines;

    /**
     * Transcription constructor.
     * @param array|null $textArray
     */
    public function __construct(?array $textArray)
    {
        if (isset($textArray)) {
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

    public function getArray() : array
    {
        $response = [];

        /** @var TranscriptionLine $transcriptionLine */
        foreach ($this->getTranscriptionLines() as $transcriptionLine) {
            $response[] = $transcriptionLine->getArray();
        }

        return $response;
    }

}

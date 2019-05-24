<?php


namespace App\Form;


use App\Entity\UserFile;

class TranscriptionsManger
{
    public function mapTranscriptionData($transcriptions) {
        $output = [];
        /** @var UserFile $transcription */
        foreach ($transcriptions as $transcription) {
            $temp = [];
            $temp['no'] = count($output)+1;
            $temp['id'] = $transcription->getId();
            $temp['date'] = $transcription->getUserfileCreated()->format("Y-m-d");
//            $temp['filename'] = $transcription
        }

        return $output;
    }
}
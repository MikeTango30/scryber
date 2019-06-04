<?php


namespace App\ScribeFormats;


use App\Model\Transcription;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface CtmExport
{

    public function getExportContent(Transcription $textArray) : string;
}
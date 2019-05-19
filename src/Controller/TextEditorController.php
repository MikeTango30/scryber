<?php

namespace App\Controller;

use App\Api\Tilde\Connector;
use App\Repository\TextGenerator;
use App\Repository\TranscriptionAggregator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class TextEditorController extends AbstractController
{
    public function textEditor(Request $request)
    {
        $jobId = $request->attributes->get('job_id');

        $transcriptionAggregator = new TranscriptionAggregator();
        $transcription = $transcriptionAggregator->prepareData($jobId);

        $textGenerator = new TextGenerator();
        $spanTags = $textGenerator->generateSpans($transcription);

        $connector = new Connector();
        $summary = $connector->getJobSummary($jobId);


        return $this->render("home/editScrybedText.html.twig", [
            "title" => "Scriber Editor",
            "summary" => $summary,
            "words" => $spanTags,
            "job_id" => $jobId
        ]);
    }

    public function mediaPlayer()
    {
        $filename = "demo_record.mp3";
        $filePath = getcwd().DIRECTORY_SEPARATOR.$_ENV['AUDIO_FILES_DEMO_DIR'].$filename;

        $response = new BinaryFileResponse($filePath);
        return $response;
    }

    public function saveTranscribedText()
    {
        //TODO
    }

}
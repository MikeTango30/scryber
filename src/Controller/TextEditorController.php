<?php

namespace App\Controller;

use App\Api\Tilde\Connector;
use App\Repository\TextGenerator;
use App\Repository\TranscriptionAggregator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

//        $media = file_get_contents($_ENV['AUDIO_FILES_DEMO_DIR'].'demo_record.mp3');

        return $this->render("home/editScrybedText.html.twig", [
            "title" => "Scriber Editor",
            "summary" => $summary,
            "words" => $spanTags,
            "job_id" => $jobId
//            "media" => $media
        ]);
    }

//    public function mediaPlayer()
//    {
//        $media = file_get_contents($_ENV['AUDIO_FILES_DEMO_DIR'].'demo_record.mp3');
//
//        return $this->render('home/editScrybedText.html.twig', [
//            "title" => "Scriber Editor",
//            "media" => $media
//    ]);
//    }

//    public function saveTranscribedText()
//    {
//
//        $savedText = strip_tags(html_entity_decode($_POST['editorText']));
//        file_put_contents(__DIR__."/../../assets/edited.txt", $savedText);
//
//        return $this->render("home/saved.html.twig", [
//            "title" => "Saved Text",
//            "text" => $savedText
//        ]);
//    }
//
//
//    public function downloadTranscribedText()
//    {
//
//    }
}
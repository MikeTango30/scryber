<?php

namespace App\Controller;

use App\Api\Tilde\Connector;
use App\Api\Tilde\SummaryModel;
use App\Entity\UserFile;
use App\Model\Transcription;
use App\Repository\TextGenerator;
use App\Repository\TranscriptionAggregator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class TextEditorController extends AbstractController
{
    public function textEditor(string $userfileId, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var UserFile $userfile */
        $userfile = $entityManager->getRepository(UserFile::class)->find($userfileId);

        $transcription = new Transcription($userfile->getUserfileText());

        $textGenerator = new TextGenerator();
        $spanTags = $textGenerator->generateSpans($transcription);

//        $connector = new Connector();
//        $summary = new SummaryModel()


        return $this->render("home/editScrybedText.html.twig", [
            "title" => "Scriber Editor",
            "summary" => [],//$summary,
            "words" => $spanTags,
            "job_id" => $userfileId
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
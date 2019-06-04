<?php

namespace App\Controller;

use App\Api\Tilde\Connector;
use App\Api\Tilde\SummaryModel;
use App\Entity\UserFile;
use App\Error\UserFileNotFoundMessage;
use App\Model\Transcription;
use App\Repository\TextGenerator;
use App\Repository\TranscriptionAggregator;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TextEditorController extends AbstractController
{
    public function textEditor(string $userfileId, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var UserFile $userfile */
        $userfile = $entityManager->getRepository(UserFile::class)->findOneBy(['id' => $userfileId, 'user' => $this->getUser()]);

        if($userfile) {

            $originalFile = $userfile->getFile();
            $transcription = new Transcription($userfile->getText());

            $confidence = $originalFile->getConfidence();
            $words = $originalFile->getWordsCount();

            $textGenerator = new TextGenerator();
            $spanTags = $textGenerator->generateSpans($transcription);


        return $this->render("home/editScrybedText.html.twig", [
            "title" => "Scriber Redaktorius",
            'userfileId' => $userfileId,
            "words" => $spanTags,
            'fileName' => $userfile->getTitle(),
            'confidence' => $confidence,
            'wordCount' => $words,
        ]);
    }

        return $this->render("home/userfileNotFound.html.twig", [
            "title" => "Scriber Editor",
        ]);
    }

    public function mediaPlayer()
    {
        $filename = "demo_record.mp3";
        $filePath = getcwd().DIRECTORY_SEPARATOR.$_ENV['AUDIO_FILES_DEMO_DIR'].$filename;

        $response = new BinaryFileResponse($filePath);
        return $response;
    }

    public function saveTranscribedText(string $userfileId, EntityManagerInterface $entityManager, Request $request)
    {
        if ($request->isMethod('POST') && $request->request->has('text')) {
            $text = $request->request->get('text');

            $scrybeForSave = new Transcription(null);
        }

        return new JsonResponse(['saved' => true]);
    }

}
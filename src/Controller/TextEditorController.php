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
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class TextEditorController extends AbstractController
{
    public function textEditor(string $userfileId, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var UserFile $userfile */
        $userfile = $entityManager->getRepository(UserFile::class)->findOneBy(['id' => $userfileId, 'user' => $this->getUser()]);

        if ($userfile) {
            $originalFile = $userfile->getFile();
            $transcription = new Transcription($userfile->getText());

            $confidence = $originalFile->getConfidence();
            $words = $originalFile->getWordsCount();

            $textGenerator = new TextGenerator();
            $spanTags = $textGenerator->generateSpans($transcription);

            return $this->render("home/editScrybedText.html.twig", [
            'title' => 'Scriber Redaktorius',
            'words' => $spanTags,
            'fileName' => $userfile->getTitle(),
            'confidence' => $confidence,
            'wordCount' => $words,
            'userfileId' => $userfileId,
            ]);
        }

        return $this->render("home/userfileNotFound.html.twig", [
            "title" => "Scriber Editor",
        ]);
    }

    public function saveTranscribedText(string $userfileId)
    {
        //TODO
    }

}
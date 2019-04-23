<?php

namespace App\Controller;

use App\Repository\TextGenerator;
use App\Repository\TranscriptionAggregator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TextEditorController extends AbstractController
{
    /**
     * @Route("/editor", name="editor")
     */

    public function textEditor()
    {

        $transcriptionAggregator = new TranscriptionAggregator();
        $transcription = $transcriptionAggregator->prepareData();

        $textGenerator = new TextGenerator();
        $htmlTranscriptionWordBlocks = $textGenerator->generateHtml($transcription);

        return $this->render("home/editor.html.twig", [
            "title" => "Scriber Editor",
            "words" => $htmlTranscriptionWordBlocks
        ]);
    }

//    /**
//     * @Route("/save", name="save")
//     */

//    public function textSave()
//    {
//        $showSavedText = strip_tags($_POST['editor_content']);
//
//        return $this->render("home/savedFroalaContent.html.twig", [
//            "title" => "Saved Text",
//            "info" => "Froal'oje parašytas tekstas perduodamas html formatu į BE
//                          per POST masyvą, editor_content indekse:",
//            "text" => html_entity_decode($showSavedText)
//        ]);
//    }
}
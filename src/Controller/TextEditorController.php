<?php

namespace App\Controller;

use App\Repository\TextGenerator;
use App\Repository\TranscriptionAggregator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TextEditorController extends AbstractController
{
//    /**
//     * @Route("/editor", name="editor")
//     */

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

    public function textSave()
    {

        $savedText = strip_tags(html_entity_decode($_POST['editor']));
        file_put_contents(__DIR__."/../../assets/edited.txt", $savedText);

        return $this->render("home/saved.html.twig", [
            "title" => "Saved Text",
            "text" => $savedText
        ]);
    }
}
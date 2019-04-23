<?php

namespace App\Controller;


use App\Entity\Transcription;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TextEditorController extends AbstractController
{
    /**
     * @Route("/editor", name="editor")
     */

    public function textEditor()
    {
        /**
         * prepare CTM as Transcription object
         */
        $transcribedCtm = file_get_contents("../assets/transcribed.ctm");
        $lines = explode("\n", $transcribedCtm);

        $lines2 = [];
        foreach ($lines as $line) {
            $lines2 [] = explode(" ", $line);
        }
        foreach ($lines2 as $item) {
                $transcription [] = new Transcription($item[2], $item[3], $item[4], $item[5]);
            }

        /**
         * create span data attributes
         */
        foreach ($transcription as $element) {
            $dataParts [] = [
                "data-word-start" . "='" . $element->getWordStart() . "'",
                "data-word-end" . "='" . $element->getWordEnd() . "'",
                "data-word-conf" . "='" . $element->getConfidenceScore() . "'"
            ];
        }
        foreach ($dataParts as $dataPart) {
            $htmlDataParts [] = implode(" ", $dataPart);
    }
        /**
         * create transcribed words array
         */
        $words = file_get_contents(__DIR__."/../../assets/transcribed.txt");
        $words = trim(preg_replace('/\s+/', ' ', $words )); // get rid of new lines
        $words = explode(" ", $words);

        /**
         * generate html div and span blocks
         * 1st foreach generates html for blocks part until $word
         * 2nd - generates $word and closing tags
         */
        foreach ($htmlDataParts as $htmlDataPart) {
            $htmlSpanAttributes [] = <<<HTML
            <div style="display:inline" contenteditable="true">
                <span $htmlDataPart>
HTML;
        }
        foreach ($words as $word) {
            $htmlTranscribedWords [] = <<<HTML
                      $word
                 </span>
             </div>   
HTML;
        }

        /**
         * combine arrays values of generated html blocks
         */
        $htmlContent = [];
        foreach ($htmlSpanAttributes as $key => $htmlSpanAttribute) {
            $htmlTranscribedWord = $htmlTranscribedWords[$key];
            $htmlContent[$key] = $htmlSpanAttribute . $htmlTranscribedWord;
        }

        return $this->render("home/editor.html.twig", [
            "title" => "Scriber Editor",
            "words" => $htmlContent
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
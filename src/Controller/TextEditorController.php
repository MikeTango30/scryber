<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TextEditorController extends AbstractController
{
    /**
     * @Route("/editor", name="editor")
     */

    public function textEditor()
    {
        $text = "Šis tekstas yra perduodamas čia po sėkmingo transkribavimo";

        return $this->render("home/editor.html.twig", [
            "title" => "Scriber Editor",
            "text" => $text
        ]);
    }

    /**
     * @Route("/save", name="save")
     */

    public function textSave()
    {
        $showSavedText = $_POST['editor_content'];

        return $this->render("home/savedFroalaContent.html.twig", [
            "title" => "Saved Text",
            "text" => $showSavedText

        ]);
    }
}
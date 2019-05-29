<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'title' => 'Scriber',
        ]);
    }

    public function about()
    {
        return $this->render('home/about.html.twig', [
            'title' => 'Apie mus'
        ]);
    }

    public function editor()
    {
        return $this->render('home/editor.html.twig', [
            'title' => 'Redaktorius'
        ]);
    }

    public function upload()
    {
        return $this->render('home/upload.html.twig', [
            'title' => 'Įkelti failą'
        ]);
    }
}

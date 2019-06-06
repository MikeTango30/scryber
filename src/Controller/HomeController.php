<?php

namespace App\Controller;

use App\Api\FileOperator\FileOperator;
use App\Entity\User;
use App\Form\FileUploadManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

    public function upload(Request $request)
    {
        $uploadError = false;
        $entityManager = $this->getDoctrine()->getManager();


        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $response = $this->render('home/upload.html.twig', [
            'title' => 'Įkelti failą',
            'text' => !$user ? 'Please login first' : $uploadError
        ]);

        if (!$user || $request->files->has('uploadedFile')) {
            $fileOperator = new FileUploadManager($user, $entityManager);
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('uploadedFile');
            if ($uploadedFile->getError() === 0) {
                if ($fileOperator->processUploadFile($uploadedFile)) {
                    //upload sekmingas, keliaujame i dashboard
                    $response = $this->redirectToRoute('user_dashboard');
                } else {
                    $uploadError = '';
                    $response = $this->render('home/upload.html.twig', [
                        'title' => 'Įkelti failą',
                        'text' => !$user ? 'Please login first' : $uploadError
                    ]);
                }
            } else {
                $uploadError = $uploadedFile->getErrorMessage();
                $response = $this->render('home/upload.html.twig', [
                    'title' => 'Įkelti failą',
                    'text' => !$user ? 'Please login first' : $uploadError
                ]);
            }
        }


        return $response;
    }
}

<?php

namespace App\Controller;

use App\Entity\UserFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    public function mediaPlayer(string $userfileId, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $userfile = $entityManager->getRepository(UserFile::class)->findOneBy(['id' => $userfileId, 'user' => $user]);

        $response = new \Symfony\Component\HttpFoundation\Response('', 404);
        if ($userfile) {
            $mediaFile = $userfile->getFile();
            $filePath = $_ENV['AUDIO_FILES_UPLOAD_DIR'] . $mediaFile->getFilePathName();
            $response = new BinaryFileResponse($filePath);
        }

        return $response;
    }
}

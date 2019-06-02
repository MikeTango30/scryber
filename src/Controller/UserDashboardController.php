<?php


namespace App\Controller;


use App\Entity\File;
use App\Entity\User;
use App\Entity\UserFile;
use App\Repository\UserFileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserDashboardController extends AbstractController
{
    public function showTranscriptions()
    {
        $transcriptions = [];
        $remainingTime = [];

        $entityManager = $this->getDoctrine()->getManager();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user */
        $user = $this->getUser();

        $user_data = $entityManager->getRepository(User::class)->find($user->getId());
        /** @var User $user_data */
        $transcription_container = $user_data->getUserFiles();
        foreach ($transcription_container as $transcript_container) {
            /** @var UserFile $transcription_container */
            $temp['no'] = count($transcriptions)+1;
            /** @var File $originalFile */
            $originalFile = $transcript_container->getUserfileFileId();
            $temp['id'] = $transcript_container->getId();
            $temp['date'] = $transcript_container->getUserfileCreated()->format("Y-m-d");
            /** @var File $temp_file */
            $temp_file = $entityManager->getRepository(File::class)->find($transcript_container->getUserfileFileId());
            $temp['title'] = $transcript_container->getUserfileTitle();
            $temp_length = new \DateInterval(sprintf("PT%dS", $temp_file->getFileLength()));
            $temp['length'] = sprintf("%02d:%02d:%02d", $temp_length->h, $temp_length->i, $temp_length->s);
            $temp['isScrybed'] = $transcript_container->getUserfileIsScrybed();
            $temp['updated'] = $transcript_container->getUserfileUpdated()->format("Y-m-d");
            $transcriptions[] = $temp;
        }

        $remainingTime = $user->getCredits();
        $remainingMinutes = floor($remainingTime/60);
        $remainingSec = $remainingTime - $remainingMinutes * 60;

        return $this->render('userDashboard.html.twig', [
            "title" => "Mano Transkripcijos",
            "transcriptions" => $transcriptions,
            "remainingTime" => $remainingTime,
            'credits_left' => sprintf("%02d:%02d", $remainingMinutes, $remainingSec),
        ]);
    }

    public function exportTranscription()
    {
        //TODO
    }

    public function uploadFile()
    {
        //TODO
    }

    public function buyTime()
    {
        //TODO
    }

    public function logout()
    {
        //TODO render homepage
        // return $this->render();
    }
}
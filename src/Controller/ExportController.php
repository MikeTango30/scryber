<?php

namespace App\Controller;

use App\Entity\UserFile;
use App\Model\Transcription;
use App\ScribeFormats\CtmExport\ExportSrt;
use App\ScribeFormats\CtmExport\ExportTxt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

class ExportController extends AbstractController
{
    /**
     * @param string $userfileId
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function exportTxt(string $userfileId, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var UserFile $userfile */
        $userfile = $entityManager->getRepository(UserFile::class)->findOneBy(['id' => $userfileId, 'user' => $this->getUser()]);;

        if ($userfile) {
            $transcription = new Transcription($userfile->getText());

            $txtExorter = new ExportTxt();

            $contents = $txtExorter->getExportContent($transcription);

            $response = new Response($contents);

            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $userfile->getTitle() . '_scribed.txt'
            );

            $response->headers->set('Content-Disposition', $disposition);
        }
        else {
            $response = $this->render("home/errorPage.html.twig", [
                "title" => "Scriber Editor",
                "error" => "Pasirinktas failas nerastas."
            ]);
        }

        return $response;
    }

    /**
     * @param string $userfileId
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function exportSrt(string $userfileId, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var UserFile $userfile */
        $userfile = $entityManager->getRepository(UserFile::class)->findOneBy(['id' => $userfileId, 'user' => $this->getUser()]);

        if ($userfile) {
            $transcription = new Transcription($userfile->getText());

            $txtExorter = new ExportSrt();

            $contents = $txtExorter->getExportContent($transcription);

            $response = new Response($contents);

            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $userfile->getTitle() . '_scribed.srt'
            );

            $response->headers->set('Content-Disposition', $disposition);
        }
        else {
            $response = $this->render("home/errorPage.html.twig", [
                "title" => "Scriber Editor",
                "error" => "Pasirinktas failas nerastas."
            ]);
        }

        return $response;
    }
}

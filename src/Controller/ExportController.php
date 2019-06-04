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
        $userfile = $entityManager->getRepository(UserFile::class)->find($userfileId);

        $transcription = new Transcription($userfile->getUserfileText());

        $txtExorter = new ExportTxt();

        $contents = $txtExorter->getExportContent($transcription);

        $response = new Response($contents);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $userfile->getUserfileTitle().'_scribed.txt'
        );

        $response->headers->set('Content-Disposition', $disposition);

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
        $userfile = $entityManager->getRepository(UserFile::class)->find($userfileId);

        $transcription = new Transcription($userfile->getUserfileText());

        $txtExorter = new ExportSrt();

        $contents = $txtExorter->getExportContent($transcription);

        $response = new Response($contents);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $userfile->getUserfileTitle().'_scribed.srt'
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}

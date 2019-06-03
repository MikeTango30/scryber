<?php

namespace App\Controller;


use App\Api\FileOperator\FileOperator;
use App\Api\Tilde\Connector;
use App\Api\Tilde\CtmLine;
use App\Api\Tilde\CtmModel;
use App\Api\Tilde\RequestModel;
use App\Api\Tilde\ResponseModel;
use App\Api\Tilde\SummaryModel;
use App\Entity\CreditLogActions;
use App\Entity\File;
use App\Entity\User;
use App\Entity\UserCreditLog;
use App\Entity\UserFile;
use App\Form\FileUploadManager;
use App\Pricing\CreditUpdates;
use App\Repository\WordBlockGenerator;
use App\ScribeFormats\CtmTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConnectionController extends AbstractController
{
    const ERROR_NO_CREDITS = 'no_credits';

    public function sendFileToScrybe(/*File */$file)
    {
        $connector = new Connector();
        $fileOperator = new FileUploadManager(null, null);
        $request = new RequestModel($fileOperator->getBasePath() . $_ENV['AUDIO_FILES_UPLOAD_DIR'] . $file->getDir() . $file->getName());
        $response = $connector->sendFile($request);

        return $response;
    }

    public function refreshStatus(string $userfileId)
    {
        /** @var UserFile $userFile */
        $userFile = $this->getDoctrine()->getRepository(UserFile::class)->find($userfileId);
        $originalFile = $userFile->getFile();

        $connector = new Connector();
        /** @var ResponseModel $response */
        $response = $connector->checkJobStatus($originalFile->getJobId());

        if ($response->getResponseStatus() == ResponseModel::SUCCESS) {
//            $this->showResults($jobId);
            //saugome

            return $this->forward('App\Controller\ConnectionController::showResults', [
                'userfileId' => $userfileId,
                'redirected' => true,
            ]);
        }

        return $this->render('home/checkScrybeStatus.html.twig', [
            'userfileId' => $userfileId,
            'job_status' => $response->getResponseStatusText(),
            'fileName' => $userFile->getTitle()
        ]);
    }

    public function showResults(string $userfileId, bool $redirected = false)
    {
        $entityManager = $this->getDoctrine()->getManager();

        /** @var UserFile $userFile */
        $userFile = $this->getDoctrine()->getRepository(UserFile::class)->find($userfileId);
        $originalFile = $userFile->getFile();
        $connector = new Connector();
        if ($redirected) { //reiskia, kad katik baige transkcibcijas, saugome pirmiausia rezultatus
            $summary = $connector->getJobSummary($originalFile->getJobId());
            $text = $connector->getScrybedTxt($originalFile->getJobId());
            $ctm = $connector->getScrybedCtm($originalFile->getJobId());
            if (empty($originalFile->getDefaultCtm())) {
                $originalFile->setDefaultCtm($ctm->getRawCtm());
                $originalFile->setPlainText($text);
                $originalFile->setWordsCount($summary->getWords());
                $originalFile->setConfidence($summary->getConfidence());
                $entityManager->persist($originalFile);
            }
            if (empty($userFile->getText())) {
                $ctmTransformer = new CtmTransformer();
                $userFile->setText($ctmTransformer->getCtmJson($originalFile));
                $userFile->setUpdated(new \DateTime());
                $userFile->setScrybeStatus(2);
                $entityManager->persist($userFile);

                $creditUpdater = new CreditUpdates($entityManager);
                $creditUpdater->chageUserCreditTotal($this->getUser(), $originalFile->getLength() * -1);
                $creditUpdater->saveUserCreditChangeLog($this->getUser(), $originalFile->getLength() * -1, $originalFile);

            }
            $lengthRounded = $summary->getLengthRounded();
            $confidence = $summary->getConfidence();
            $words = $summary->getWords();

            $entityManager->flush();

//            $this->saveCreditLog($originalFile->getFileLength(), true, $userFile, $entityManager);
        }
        else {
            $lengthRounded = $originalFile->getLength();
            $confidence = $originalFile->getConfidence();
            $words = $originalFile->getWordsCount();
            $text = $originalFile->getPlainText();
            $ctm = new CtmModel($originalFile->setDefaultCtm());
        }


        return $this->render('home/showScrybedText.html.twig', [
            'userfileId' => $userfileId,
            'fileName' => $userFile->getTitle(),
            'length' => $lengthRounded,
            'confidence' => $confidence,
            'words' => $words,
            'text' => $text,
            'ctm' => $ctm,
        ]);

    }

    public function processScrybeFile(string $userfileId, EntityManagerInterface $entityManager)
    {
        /**
         *  1. pasigetiname faila
         * 2. patikriname ar jis jau turi vertima. jeigu taip, tuomet ji uzsetinkime vartotojo vertimui, Done.
         * 3. jeigu neturi vertimo - siuskime ji i API
         * 4. sumazinkime kreditus.
         */

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user */
        $user = $this->getUser();

        /** @var UserFile $userFile */
        $userFile = $entityManager->getRepository(UserFile::class)->find($userfileId);
        $originalFile = $userFile->getFile();

        if ($user->getCredits() < $originalFile->getLength()) {
            return self::ERROR_NO_CREDITS;
        }

        if (empty($originalFile->getDefaultCtm())) {
            $result = $this->sendFileToScrybe($originalFile);

            $originalFile->setJobId($result->getRequestId());
            $entityManager->flush();
        }

        return $this->redirectToRoute('check_scrybe_status', ['userfileId' => $userfileId]);
    }
}


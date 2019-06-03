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
use App\Model\Transcription;
use App\Repository\TextGenerator;
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

    public function sendFileToScrybe(File $file)
    {
        $connector = new Connector();
        $fileOperator = new FileUploadManager(null, null);
        $request = new RequestModel($fileOperator->getBasePath() . $_ENV['AUDIO_FILES_UPLOAD_DIR'] . $file->getFileDir() . $file->getFileName());
        $response = $connector->sendFile($request);

        return $response;
    }

    public function refreshStatus(string $userfileId, \Swift_Mailer $mailer)
    {
        /** @var UserFile $userFile */
        $userFile = $this->getDoctrine()->getRepository(UserFile::class)->find($userfileId);
        $originalFile = $userFile->getUserfileFileId();

        $connector = new Connector();
        /** @var ResponseModel $response */
        $response = $connector->checkJobStatus($originalFile->getFileJobId());
        $responseStatus = $response->getResponseStatus();

        if ($responseStatus == ResponseModel::SUCCESS) {
//            $this->showResults($jobId);
            //saugome

            $this->sendEmail($userfileId, $mailer);


            return $this->forward('App\Controller\ConnectionController::showResults', [
                'userfileId' => $userfileId,
                'redirected' => true,
            ]);
        }

        return $this->render('home/checkScrybeStatus.html.twig', [
            'userfileId' => $userfileId,
            'job_status' => $response->getResponseStatusText(),
            'fileName' => $userFile->getUserfileTitle()
        ]);
    }

    public function sendEmail( string $userfileId, \Swift_Mailer $mailer): void
    {
        $user = $this->getUser();
        $message = (new \Swift_Message('Scriber'))
            ->setFrom('scriber.assistant@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($this->renderView('emails/transcribed.html.twig', [
                    'name' => $user->getFirstname(),
                    'userfileId' => $userfileId
                ]
            ),
                'text/html'
            );

        $mailer->send($message);
    }

    public function showResults(string $userfileId, bool $redirected = false)
    {
        $entityManager = $this->getDoctrine()->getManager();

        /** @var UserFile $userFile */
        $userFile = $this->getDoctrine()->getRepository(UserFile::class)->find($userfileId);
        $originalFile = $userFile->getUserfileFileId();
        $connector = new Connector();
        if ($redirected) { //reiskia, kad katik baige transkcibcijas, saugome pirmiausia rezultatus
            $summary = $connector->getJobSummary($originalFile->getFileJobId());
            $text = $connector->getScrybedTxt($originalFile->getFileJobId());
            $ctm = $connector->getScrybedCtm($originalFile->getFileJobId());
            if (empty($originalFile->getFileDefaultCtm())) {
                $originalFile->setFileDefaultCtm($ctm->getRawCtm());
                $originalFile->setFileTxt($text);
                $originalFile->setFileWords($summary->getWords());
                $originalFile->setFileConfidence($summary->getConfidence());
                $entityManager->persist($originalFile);
            }
            if (empty($userFile->getUserfileText())) {
                $ctmTransformer = new CtmTransformer();
                $userFile->setUserfileText($ctmTransformer->getCtmJson($originalFile));
                $userFile->setUserfileUpdated(new \DateTime());
                $userFile->setUserfileIsScrybed(1);
                $entityManager->persist($userFile);
            }
//            $lengthRounded = $summary->getLengthRounded();
            $confidence = $summary->getConfidence();
            $words = $summary->getWords();

            $entityManager->flush();

            $this->saveCreditLog($originalFile->getFileLength(), true, $userFile, $entityManager);
        }
        else {
//            $lengthRounded = $originalFile->getFileLength();
            $confidence = $originalFile->getFileConfidence();
            $words = $originalFile->getFileWords();
//            $text = $originalFile->getFileTxt();
//            $ctm = new CtmModel($originalFile->setFileDefaultCtm());
        }

        $transcription = new Transcription($userFile->getUserfileText());

        $textGenerator = new TextGenerator();
        $spanTags = $textGenerator->generateSpans($transcription);

        return $this->render("home/editScrybedText.html.twig", [
            "title" => "Scriber Redaktorius",
            "words" => $spanTags,
            'fileName' => $userFile->getUserfileTitle(),
            'confidence' => $confidence,
            'wordCount' => $words,
        ]);

//        return $this->render('home/showScrybedText.html.twig', [
//            'userfileId' => $userfileId,
//            'fileName' => $userFile->getUserfileTitle(),
//            'length' => $lengthRounded,
//            'confidence' => $confidence,
//            'words' => $words,
//            'text' => $text,
//            'ctm' => $ctm,
//        ]);

    }

    public function DownloadTxtFile(string $jobId)
    {
        $connector = new Connector();
        $text = $connector->getScrybedTxt($jobId);

        $response = new Response($text);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'scrybed_text.txt'
        );

        $response->headers->set('Content-Disposition', $disposition);
        return $response;
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
        $originalFile = $userFile->getUserfileFileId();

        if ($user->getCredits() < $originalFile->getFileLength()) {
            return self::ERROR_NO_CREDITS;
        }

        if (empty($originalFile->getFileDefaultCtm())) {
            $result = $this->sendFileToScrybe($originalFile);

            $originalFile->setFileJobId($result->getRequestId());
            $entityManager->flush();
        }

        return $this->redirectToRoute('check_scrybe_status', ['userfileId' => $userfileId]);
    }

    public function saveCreditLog(int $credits, bool $payOut = true, UserFile $userFile = null, EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        $actionName = $payOut ? 'Scrybe_file' : 'Top_up_credits';
        $logAction = $entityManager->getRepository(CreditLogActions::class)->findOneBy(['claName' => $actionName]);

        $operationLog = new UserCreditLog();
        $operationLog->setUclCreated(new \DateTime());
        $operationLog->setUclCredits($credits * ($payOut ? -1 : 1));
        $operationLog->setUclUserfileId($userFile);
        $operationLog->setUclUserId($user);
        $operationLog->setUclActionId($logAction);

        $entityManager->persist($operationLog);
        $entityManager->flush();
    }

    private function makeCtmJson(File $file) : array
    {
        $ctm = new CtmModel($file->getFileDefaultCtm());
        $text = $file->getFileTxt();
        $words = explode(' ', $text);
        $wordsCount = $file->getFileWords();

        if(count($words)!==$wordsCount)
            return new \Exception("Unable to format JSON from CTM source", 1);

        $jsonObject = [];

        $index = 0;
        /** @var CtmLine $_ctm */
        foreach ($ctm->getCtm() as $_ctm) {
            $jsonLine = new \stdClass();
            $jsonLine->duration = $_ctm->getDuration();
            $jsonLine->beginTime = $_ctm->getBeginTime();
            $jsonLine->confidence = $_ctm->getConfidence();
            $jsonLine->word = $words[$index];//$_ctm->getWordId();

            array_push($jsonObject, $jsonLine);

            $index++;
        }

        return $jsonObject;
    }
}


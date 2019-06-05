<?php

namespace App\Controller;


use App\Api\FileOperator\FileOperator;
use App\Api\Tilde\Connector;
use App\Api\Tilde\RequestModel;
use App\Api\Tilde\ResponseModel;
use App\Entity\CreditLogActions;
use App\Entity\File;
use App\Entity\User;
use App\Entity\UserCreditLog;
use App\Entity\UserFile;
use App\Form\FileUploadManager;
use App\Pricing\CreditUpdates;
use App\Model\Transcription;
use App\Repository\TextGenerator;
use App\Repository\WordBlockGenerator;
use App\ScribeFormats\CtmTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ConnectionController extends AbstractController
{
    const ERROR_NO_CREDITS = 'no_credits';

    public function sendFileToScrybe(File $file)
    {
        $connector = new Connector();
        $fileOperator = new FileUploadManager(null, null);
        $request = new RequestModel($fileOperator->getBasePath() . $_ENV['AUDIO_FILES_UPLOAD_DIR'] . $file->getDir() . $file->getName());
        $response = $connector->sendFile($request);

        return $response;
    }

    public function refreshStatus(string $userfileId, \Swift_Mailer $mailer)
    {
        /** @var UserFile $userFile */
        $userFile = $this->getDoctrine()->getRepository(UserFile::class)->find($userfileId);
        $originalFile = $userFile->getFile();

        $connector = new Connector();
        /** @var ResponseModel $response */
        $response = $connector->checkJobStatus($originalFile->getJobId());
        $responseStatus = $response->getResponseStatus();

        if ($responseStatus == ResponseModel::SUCCESS) {
//            $this->showResults($jobId);
            //saugome

            $this->sendEmail($userfileId, $mailer);


            return $this->redirectToRoute('show_scrybed_results', [
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

    public function saveResults(string $userfileId, bool $redirected = false)
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
                $userFile->setScrybeStatus(UserFile::SCRYBE_STATUS_COMPLETED);
                $entityManager->persist($userFile);

                $creditUpdater = new CreditUpdates($entityManager);
                $creditUpdater->chageUserCreditTotal($this->getUser(), $originalFile->getLength() * -1);
                $creditUpdater->saveUserCreditChangeLog($this->getUser(), $originalFile->getLength() * -1, $originalFile);

            }
//            $lengthRounded = $summary->getLengthRounded();
            $confidence = $summary->getConfidence();
            $words = $summary->getWords();

            $entityManager->flush();

//            $this->saveCreditLog($originalFile->getFileLength(), true, $userFile, $entityManager);
        }
        else {
//            $lengthRounded = $originalFile->getLength();
            $confidence = $originalFile->getConfidence();
            $words = $originalFile->getWordsCount();
//            $text = $originalFile->getPlainText();
//            $ctm = new CtmModel($originalFile->setDefaultCtm());
        }


        return $this->redirectToRoute('edit_scribed_text', ['userfileId' => $userfileId]);

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

    public function checkTranscriptionStatus(string $userfileId)
    {
        /** @var UserFile $userFile */
        $userFile = $this->getDoctrine()->getRepository(UserFile::class)->find($userfileId);
        $originalFile = $userFile->getFile();

        $connector = new Connector();
        /** @var ResponseModel $response */
        $response = $connector->checkJobStatus($originalFile->getJobId());
        $responseStatus = $response->getResponseStatus();

        $response = new Response('1', 200);

        if ($responseStatus == ResponseModel::SUCCESS) {

            $response = new Response('0', 200);

        }

        return $response;
    }
}


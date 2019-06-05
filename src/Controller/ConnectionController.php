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
use App\Repository\WordBlockGenerator;
use App\ScribeFormats\CtmTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConnectionController extends AbstractController
{
    const ERROR_NO_CREDITS = 'no_credits';

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
            $this->sendEmail($userfileId, $mailer);

            return $this->forward('App\Controller\ConnectionController::saveResults', [
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

    public function sendEmail(string $userfileId, \Swift_Mailer $mailer): void
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

    public function saveResults(string $userfileId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $uploadManager = new FileUploadManager($this->getUser(), $entityManager);
        $uploadManager->saveScrybeResults($userfileId);

        return $this->redirectToRoute('edit_scribed_text', ['userfileId' => $userfileId]);

    }

    public function processScrybeFile(string $userfileId, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        /** @var User $user */
        $user = $this->getUser();

        /** @var UserFile $userFile */
        $userFile = $entityManager->getRepository(UserFile::class)->findOneBy(['id'=>$userfileId, 'user'=>$user]);

        $response = $this->render("home/errorPage.html.twig", [
            "title" => "Scriber Editor",
            "error" => "Klaida. Pasirinktas failas nerastas."
        ]);

        if ($userFile) {
            $uploadManager = new FileUploadManager($this->getUser(), $entityManager);
            $uploadStatus = $uploadManager->processScrybeFile($userFile);
            if (empty($uploadStatus['error'])) {
                $response = $this->redirectToRoute('check_scrybe_status', ['userfileId' => $userfileId]);
            }
            else {
                $response = $this->render("home/errorPage.html.twig", [
                    "title" => "Scriber Editor",
                    "error" => $uploadStatus['error']
                ]);
            }
        }

        return $response;
    }

    public function checkTranscriptionStatus(string $userfileId)
    {
        /** @var UserFile $userFile */
        $userFile = $this->getDoctrine()->getRepository(UserFile::class)->findOneBy(['id' => $userfileId, 'user' => $this->getUser()]);

        $originalFile = $userFile->getFile();

        $connector = new Connector();
        /** @var ResponseModel $response */
        $response = $connector->checkJobStatus($originalFile->getJobId());
        $responseStatus = $response->getResponseStatus();
        $responseMessage = $response->getResponseStatusText();
        $finished = in_array($responseStatus, [ResponseModel::SUCCESS, ResponseModel::NO_SPEECH, ResponseModel::DECODING_ERROR, ResponseModel::ERROR, ResponseModel::TYPE_NOT_RECOGNIZED]);

        $response = new JsonResponse(['redirecting' => $finished, 'message' => $responseMessage]);

        return $response;
    }
}



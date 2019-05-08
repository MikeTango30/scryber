<?php

namespace App\Controller;


use App\Api\FileOperator\FileOperator;
use App\Api\Tilde\Connector;
use App\Api\Tilde\RequestModel;
use App\Api\Tilde\ResponseModel;
use App\Repository\WordBlockGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnectionController extends AbstractController
{
//    /**
//     * @Route("/", name="connection")
//     */
    public function showStaticFileUploadPage()
    {
        $message = '';

//        $output .= $package->getUrl('/demo_record.m4a');
        return $this->render('home/uploadDemoFile.html.twig', [
            'soundFile' => 'demo_record.m4a',
            'message' => $message
        ]);
    }

    public function sendDemoFile(Request $request)
    {

        $file_name = $request->request->get('upload_demo_file');
        if (!empty($file_name)) {
            $file_operator = new FileOperator();
            $file_path = $file_operator->UploadFileToServer($_ENV['AUDIO_FILES_DEMO_DIR'] . $file_name, false);
            if ($file_path && file_exists($file_path)) {
                $connector = new Connector();
                $request = new RequestModel($file_path);
                /** @var ResponseModel $response */
                $response = $connector->sendFile($request);

                if ($response) {
                    return $this->forward('App\Controller\ConnectionController::refreshStatus', [
                        'jobId' => $response->getRequestId()
                    ]);
                }
            }
        }

        $message = 'No file found!';
        return $this->render('home/uploadDemoFile.html.twig', [
            'soundFile' => 'demo_record.m4a',
            'message' => $message
        ]);

    }

    public function refreshStatus(string $jobId)
    {
        $connector = new Connector();
        /** @var ResponseModel $response */
        $response = $connector->checkJobStatus($jobId);

        if ($response->getResponseStatus() == ResponseModel::SUCCESS) {
//            $this->showResults($jobId);
            return $this->forward('App\Controller\ConnectionController::showResults', [
                'jobId' => $jobId
            ]);
        }

        return $this->render('home/checkScrybeStatus.html.twig', [
            'job_id' => $jobId,
            'job_status' => $response->getResponseStatusText()
        ]);
    }

    public function showResults(string $jobId)
    {
        $connector = new Connector();
        $summary = $connector->getJobSummary($jobId);
        $text = $connector->getScrybedTxt($jobId);
        $ctm = $connector->getScrybedCtm($jobId);

        if ($summary) {
            return $this->render('home/showScrybedText.html.twig', [
                'job_id' => $jobId,
                'summary' => $summary,
                'text' => $text,
                'ctm' => $ctm,
            ]);
        }

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
}

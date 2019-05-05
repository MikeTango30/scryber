<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserDashboardController extends AbstractController
{
    public function showTranscriptions()
    {
        $transcriptions = [];
        $remainingTime = [];
        //TODO query DB for transcriptions
        //TODO query DB for remaining Time

        return $this->render('userDashboard.html.twig', [
            "title" => "Mano Transkripcijos",
            "transcriptions" => $transcriptions,
            "remainingTime" => $remainingTime
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
<?php


namespace App\Controller;


use App\Repository\PricingPlanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PricingController extends AbstractController
{
    public function pricing(PricingPlanRepository $pricingPlanRepository)
    {
        $pricingPlans = $pricingPlanRepository->selectAllPlans();

        return $this->render('pricing/pricing.html.twig', [
            'title' => 'Kaina',
            'pricingPlans' => $pricingPlans
        ]);
    }

    public function buyHours($hours, PricingPlanRepository $pricingPlanRepository)
    {
        $errors = '';

        $pricingPlanRate = $pricingPlanRepository->findPricingPlanRate($hours);

        if ($pricingPlanRate === null){
            $errors = 'Atsiprašome, šiuo metu galime pasiūlyti pirkti transkribavimui vieną, tris arba šešias valandas';
        }

        return $this->render('pricing/checkout.html.twig', [
            'title' => 'Pirkimas',
            'errors' => $errors,
            'pricingPlanHours' => $hours,
            'pricingPlanRate' => $pricingPlanRate
        ]);
    }

    public function updateCredits($hours, PricingPlanRepository $pricingPlanRepository, \Swift_Mailer $mailer, ConnectionController $connectionController)
    {
        $pricingPlanRate = $pricingPlanRepository->findPricingPlanRate($hours);

        if ($pricingPlanRate === null){
            return $this->redirectToRoute('buy', ['hours' => $hours]);
        }

        // $userFile ir $logAction yra null, todel error'as
        //TODO $connectionController->saveCreditLog($hours * 3600, false);

        $this->sendEmail($mailer, $hours, $pricingPlanRate);

        return $this->redirectToRoute('user_dashboard');

    }

    public function sendEmail(\Swift_Mailer $mailer, $hours, $pricingPlanRate)
    {
        $user = $this->getUser();
        $userName = $user->getFirstname();
        $userEmail = $user->getEmail();

        $message = (new \Swift_Message('Scriber'))
            ->setFrom('scriber.assistant@gmail.com')
            ->setTo($userEmail)
            ->setBody($this->renderView('emails/timeBought.html.twig', [
                    'name' => $userName,
                    'pricingPlanHours' => $hours,
                    'pricingPlanRate' => $pricingPlanRate
                ]
            ),
                'text/html'
            );
        $mailer->send($message);

    }
}
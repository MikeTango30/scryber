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

    public function updateCredits($hours, PricingPlanRepository $pricingPlanRepository)
    {
        $pricingPlanRate = $pricingPlanRepository->findPricingPlanRate($hours);

        if ($pricingPlanRate === null){
            return $this->redirectToRoute('buy', ['hours' => $hours]);
        }

        //TODO update DB

        return $this->redirectToRoute('user_dashboard');

    }
}
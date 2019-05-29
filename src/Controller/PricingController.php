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

    public function buyHours($hours)
    {
        $errors = '';
        $validHours = [1, 3, 6];
        if (!in_array($hours, $validHours)) {
            $errors = ('Atsiprašome, šiuo metu galime pasiūlyti pirkti transkribavimui vieną, tris arba šešias valandas');
        }

        return $this->render('pricing/bought.html.twig', [
            'title' => 'Pirkimas',
            'errors' => $errors,
            'hours' => $hours
        ]);
    }

    public function confirmBuy()
    {

    }
}
<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PricingController extends AbstractController
{
    public function pricing()
    {
        $content = [
            'planOne' => 1,
            'planTwo' => 3,
            'planThree' => 6,
            'priceRateForOneHour' => 15,
            'priceRateForThreeHours' => 36,
            'priceRateForSixHours' => 54
        ];

        return $this->render('pricing/pricing.html.twig', [
            'title' => 'Kaina',
            'content' => $content
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
            'errors' => $errors
        ]);
    }
}
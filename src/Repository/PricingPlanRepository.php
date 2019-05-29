<?php


namespace App\Repository;


use App\Model\PricingPlan;

class PricingPlanRepository
{
    public function selectAllPlans()
    {
        $result = [];
        $pricingPlans = json_decode(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'PricingPlans' . DIRECTORY_SEPARATOR . 'PricingPlans.json'),
            true
        );

        foreach ($pricingPlans as $pricingPlan) {
            $plan = new PricingPlan();
            $plan->setPricingPlanHours($pricingPlan['planHours']);
            $plan->setPricingPlanRate($pricingPlan['planPrice']);
            $result[] = $plan;
        }

        return $result;
    }
}
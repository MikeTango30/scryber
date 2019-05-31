<?php


namespace App\Repository;


use App\Model\PricingPlan;

class PricingPlanRepository
{
    public function selectAllPlans(): array
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

    /**
     * @param int $hours
     * @return int
     */
    public function findPricingPlanRate(int $hours): ?int
    {
        $pricingPlans = $this->selectAllPlans();
        $pricingPlanRate = null;

        foreach ($pricingPlans as $pricingPlan) {
            if ($pricingPlan->getPricingPlanHours() === $hours) {
                $pricingPlanRate = $pricingPlan->getPricingPlanRate();
            }
        }

        return $pricingPlanRate;
    }
}
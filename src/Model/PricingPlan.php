<?php


namespace App\Model;


class PricingPlan
{
    /** @var int */
    protected $pricingPlanHours;

    /** @var int */
    protected $pricingPlanRate;

    /**
     * @return int
     */
    public function getPricingPlanHours(): int
    {
        return $this->pricingPlanHours;
    }

    /**
     * @param int $pricingPlanHours
     */
    public function setPricingPlanHours(int $pricingPlanHours): void
    {
        $this->pricingPlanHours = $pricingPlanHours;
    }

    /**
     * @return int
     */
    public function getPricingPlanRate(): int
    {
        return $this->pricingPlanRate;
    }

    /**
     * @param int $pricingPlanRate
     */
    public function setPricingPlanRate(int $pricingPlanRate): void
    {
        $this->pricingPlanRate = $pricingPlanRate;
    }


}
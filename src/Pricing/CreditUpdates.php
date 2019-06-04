<?php


namespace App\Pricing;


use App\Entity\CreditLogActions;
use App\Entity\User;
use App\Entity\UserCreditLog;
use App\Entity\UserFile;
use Doctrine\ORM\EntityManagerInterface;

class CreditUpdates
{
    const ERROR_NO_CREDITS = 'no_credits';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function chageUserCreditTotal(User $user, int $amount)
    {
        $response = self::ERROR_NO_CREDITS;

        $currentTotal = $user->getCredits();
        if ($currentTotal + $amount >= 0) {
            $user->setCredits($currentTotal + $amount);
            $this->entityManager->flush();
        }

        return $response;
    }

    public function saveUserCreditChangeLog(User $user, int $amount, UserFile $userFile = null)
    {
        $actionName = $amount <= 0 ? 'Scrybe_file' : 'Top_up_credits';
        $logAction = $this->entityManager->getRepository(CreditLogActions::class)->findOneBy(['claName' => $actionName]);

        $operationLog = new UserCreditLog();
        $operationLog->setUclCreated(new \DateTime());
        $operationLog->setUclCredits($amount);
        $operationLog->setUclUserfileId($userFile);
        $operationLog->setUclUserId($user);
        $operationLog->setUclActionId($logAction);

        $this->entityManager->persist($operationLog);
        $this->entityManager->flush();
    }
}
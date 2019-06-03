<?php


namespace App\Pricing;


use App\Entity\CreditLog;
use App\Entity\CreditLogAction;
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
        $actionName = $amount <= 0 ? 'scrybe_file' : 'top_up_credits';
        $logAction = $this->entityManager->getRepository(CreditLogAction::class)->findOneBy(['name' => $actionName]);

        $operationLog = new CreditLog();
        $operationLog->setCreated(new \DateTime());
        $operationLog->setAmount($amount);
        $operationLog->setUserFile($userFile);
        $operationLog->setUser($user);
        $operationLog->setAction($logAction);

        $this->entityManager->persist($operationLog);
        $this->entityManager->flush();
    }
}
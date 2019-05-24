<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserCreditLogRepository")
 */
class UserCreditLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userCreditLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $uclUserId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CreditLogActions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $uclActionId;

    /**
     * @ORM\Column(type="integer")
     */
    private $uclCredits;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserFile", inversedBy="userCreditLogs")
     */
    private $uclUserfileId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uclCreated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUclUserId(): ?User
    {
        return $this->uclUserId;
    }

    public function setUclUserId(?User $uclUserId): self
    {
        $this->uclUserId = $uclUserId;

        return $this;
    }

    public function getUclActionId(): ?CreditLogActions
    {
        return $this->uclActionId;
    }

    public function setUclActionId(?CreditLogActions $uclActionId): self
    {
        $this->uclActionId = $uclActionId;

        return $this;
    }

    public function getUclCredits(): ?int
    {
        return $this->uclCredits;
    }

    public function setUclCredits(int $uclCredits): self
    {
        $this->uclCredits = $uclCredits;

        return $this;
    }

    public function getUclUserfileId(): ?UserFile
    {
        return $this->uclUserfileId;
    }

    public function setUclUserfileId(?UserFile $uclUserfileId): self
    {
        $this->uclUserfileId = $uclUserfileId;

        return $this;
    }

    public function getUclCreated(): ?\DateTimeInterface
    {
        return $this->uclCreated;
    }

    public function setUclCreated(\DateTimeInterface $uclCreated): self
    {
        $this->uclCreated = $uclCreated;

        return $this;
    }
}

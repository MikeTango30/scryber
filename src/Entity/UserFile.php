<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserFileRepository")
 */
class UserFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\File", inversedBy="userFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userfileFileId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userfileUserId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $userfileCtm;

    /**
     * @ORM\Column(type="smallint")
     */
    private $userfileIsScrybed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $userfileCreated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $userfileUpdated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCreditLog", mappedBy="uclUserfileId")
     */
    private $userCreditLogs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userfileTitle;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $userfileText = [];

    public function __construct()
    {
        $this->userCreditLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserfileFileId(): ?File
    {
        return $this->userfileFileId;
    }

    public function setUserfileFileId(?File $userfileFileId): self
    {
        $this->userfileFileId = $userfileFileId;

        return $this;
    }

    public function getUserfileUserId(): ?User
    {
        return $this->userfileUserId;
    }

    public function setUserfileUserId(?User $userfileUserId): self
    {
        $this->userfileUserId = $userfileUserId;

        return $this;
    }

    public function getUserfileCtm(): ?string
    {
        return $this->userfileCtm;
    }

    public function setUserfileCtm(?string $userfileCtm): self
    {
        $this->userfileCtm = $userfileCtm;

        return $this;
    }

    public function getUserfileIsScrybed(): ?int
    {
        return $this->userfileIsScrybed;
    }

    public function setUserfileIsScrybed(int $userfileIsScrybed): self
    {
        $this->userfileIsScrybed = $userfileIsScrybed;

        return $this;
    }

    public function getUserfileCreated(): ?\DateTimeInterface
    {
        return $this->userfileCreated;
    }

    public function setUserfileCreated(\DateTimeInterface $userfileCreated): self
    {
        $this->userfileCreated = $userfileCreated;

        return $this;
    }

    public function getUserfileUpdated(): ?\DateTimeInterface
    {
        return $this->userfileUpdated;
    }

    public function setUserfileUpdated(\DateTimeInterface $userfileUpdated): self
    {
        $this->userfileUpdated = $userfileUpdated;

        return $this;
    }

    /**
     * @return Collection|UserCreditLog[]
     */
    public function getUserCreditLogs(): Collection
    {
        return $this->userCreditLogs;
    }

    public function addUserCreditLog(UserCreditLog $userCreditLog): self
    {
        if (!$this->userCreditLogs->contains($userCreditLog)) {
            $this->userCreditLogs[] = $userCreditLog;
            $userCreditLog->setUclUserfileId($this);
        }

        return $this;
    }

    public function removeUserCreditLog(UserCreditLog $userCreditLog): self
    {
        if ($this->userCreditLogs->contains($userCreditLog)) {
            $this->userCreditLogs->removeElement($userCreditLog);
            // set the owning side to null (unless already changed)
            if ($userCreditLog->getUclUserfileId() === $this) {
                $userCreditLog->setUclUserfileId(null);
            }
        }

        return $this;
    }

    public function getUserfileTitle(): ?string
    {
        return $this->userfileTitle;
    }

    public function setUserfileTitle(string $userfileTitle): self
    {
        $this->userfileTitle = $userfileTitle;

        return $this;
    }

    public function getUserfileText(): ?array
    {
        return $this->userfileText;
    }

    public function setUserfileText(?array $userfileText): self
    {
        $this->userfileText = $userfileText;

        return $this;
    }
}

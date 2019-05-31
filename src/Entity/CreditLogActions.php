<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreditLogActionsRepository")
 */
class CreditLogActions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $claName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClaName(): ?string
    {
        return $this->claName;
    }

    public function setClaName(string $claName): self
    {
        $this->claName = $claName;

        return $this;
    }
}

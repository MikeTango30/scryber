<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=130)
     */
    private $dir;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $length;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $md5;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=55, nullable=true)
     */
    private $job_id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $defaultCtm;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $plainText;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wordsCount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $confidence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDir(): ?string
    {
        return $this->dir;
    }

    public function setDir(string $dir): self
    {
        $this->dir = $dir;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getMd5(): ?string
    {
        return $this->md5;
    }

    public function setMd5(string $md5): self
    {
        $this->md5 = $md5;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getJobId(): ?string
    {
        return $this->job_id;
    }

    public function setJobId(?string $job_id): self
    {
        $this->job_id = $job_id;

        return $this;
    }

    public function getDefaultCtm(): ?string
    {
        return $this->defaultCtm;
    }

    public function setDefaultCtm(?string $defaultCtm): self
    {
        $this->defaultCtm = $defaultCtm;

        return $this;
    }

    public function getPlainText(): ?string
    {
        return $this->plainText;
    }

    public function setPlainText(?string $plainText): self
    {
        $this->plainText = $plainText;

        return $this;
    }

    public function getWordsCount(): ?int
    {
        return $this->wordsCount;
    }

    public function setWordsCount(?int $wordsCount): self
    {
        $this->wordsCount = $wordsCount;

        return $this;
    }

    public function getConfidence(): ?float
    {
        return $this->confidence;
    }

    public function setConfidence(?float $confidence): self
    {
        $this->confidence = $confidence;

        return $this;
    }
}

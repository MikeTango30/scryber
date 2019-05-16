<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private $fileDir;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $fileName;

    /**
     * @ORM\Column(type="integer")
     */
    private $fileLength;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $fileMd5;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fileCreated;

    /**
     * @ORM\Column(type="string", length=55, nullable=true)
     */
    private $fileJobId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $fileDefaultCtm;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFile", mappedBy="userfileFileId")
     */
    private $userFiles;

    public function __construct()
    {
        $this->userFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileDir(): ?string
    {
        return $this->fileDir;
    }

    public function setFileDir(string $fileDir): self
    {
        $this->fileDir = $fileDir;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileLength(): ?int
    {
        return $this->fileLength;
    }

    public function setFileLength(int $fileLength): self
    {
        $this->fileLength = $fileLength;

        return $this;
    }

    public function getFileMd5(): ?string
    {
        return $this->fileMd5;
    }

    public function setFileMd5(string $fileMd5): self
    {
        $this->fileMd5 = $fileMd5;

        return $this;
    }

    public function getFileCreated(): ?\DateTimeInterface
    {
        return $this->fileCreated;
    }

    public function setFileCreated(\DateTimeInterface $fileCreated): self
    {
        $this->fileCreated = $fileCreated;

        return $this;
    }

    public function getFileJobId(): ?string
    {
        return $this->fileJobId;
    }

    public function setFileJobId(?string $fileJobId): self
    {
        $this->fileJobId = $fileJobId;

        return $this;
    }

    public function getFileDefaultCtm(): ?string
    {
        return $this->fileDefaultCtm;
    }

    public function setFileDefaultCtm(?string $fileDefaultCtm): self
    {
        $this->fileDefaultCtm = $fileDefaultCtm;

        return $this;
    }

    /**
     * @return Collection|UserFile[]
     */
    public function getUserFiles(): Collection
    {
        return $this->userFiles;
    }

    public function addUserFile(UserFile $userFile): self
    {
        if (!$this->userFiles->contains($userFile)) {
            $this->userFiles[] = $userFile;
            $userFile->setUserfileFileId($this);
        }

        return $this;
    }

    public function removeUserFile(UserFile $userFile): self
    {
        if ($this->userFiles->contains($userFile)) {
            $this->userFiles->removeElement($userFile);
            // set the owning side to null (unless already changed)
            if ($userFile->getUserfileFileId() === $this) {
                $userFile->setUserfileFileId(null);
            }
        }

        return $this;
    }
}

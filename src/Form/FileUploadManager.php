<?php


namespace App\Form;

use App\Api\MediaConverter;
use App\Api\FileOperator\FileOperator;
use App\Entity\File;
use App\Entity\User;
use App\Entity\UserFile;
use App\Form\FormModel\UploadResult;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File as SymFile;

class FileUploadManager
{
    protected $basePath;

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    protected $entityManager;
    private $user;

    public function __construct(?User $user, ?ObjectManager $entityManager)
    {
        $this->basePath = getcwd() . DIRECTORY_SEPARATOR;
        $this->user = $user;
        $this->entityManager = $entityManager;
    }


    /**
     * @param UploadedFile $file
     * @return UserFile
     */
    public function processUploadFile(UploadedFile $file)
    {
        $fileMd5 = $this->generateFileHash($file->getPathname());
        $newFileDir = '';
        $newFileName = $fileMd5.'.mp4';//$file->getExtension();

        $fileExistsInDb = $this->searchFileByHash($fileMd5);
        $fileExistsInFileSystem = file_exists($this->basePath.$_ENV['AUDIO_FILES_UPLOAD_DIR']
            .$newFileDir.$newFileName);

        if (!$fileExistsInFileSystem) {
            $uploadedFile = new SymFile($this->uploadFileToServer(
                $file,
                $this->basePath.$_ENV['AUDIO_FILES_UPLOAD_DIR'].$newFileDir,
                $newFileName
            ));
        } else {
            $uploadedFile = new SymFile($this->basePath.$_ENV['AUDIO_FILES_UPLOAD_DIR'].$newFileDir.$newFileName);
        }
        if (!$fileExistsInDb) {
            $mediaConverter = new MediaConverter();
            $fileDuration = $mediaConverter->getMediaDuration($uploadedFile);
            $fileExistsInDb = $this->saveFileEntry($uploadedFile, $newFileDir, $fileDuration);
        }

        $userFile = $this->saveUserFileReference($fileExistsInDb, $file->getClientOriginalName(), $this->user);

        return $userFile;
    }

    /**
     * @param UploadedFile $file
     * @param string $newDir
     * @param string $newName
     * @return string
     */
    public function uploadFileToServer(UploadedFile $file, string $newDir, string $newName): string
    {
        $result = false;
        if ($file) {
            $mediaConverter = new MediaConverter();
            $file = $mediaConverter->ConvertFile($file);
            $result = $file->move($newDir, $newName);
        }
        return $result;
    }

    /**
     * @param string $filePath
     * @return string
     */
    public function generateFileHash(string $filePath)
    {
        return hash_file('md5', $filePath);
    }

    /**
     * @param string $md5Hash
     * @return File
     */
    public function searchFileByHash(string $md5Hash)
    {
        /** @var File $fileSearch */
        $fileSearch = $this->entityManager
            ->getRepository(File::class)
            ->findOneBy(['md5' => $md5Hash]);

        return $fileSearch;
    }

    /**
     * @param SymFile $file
     * @param string $dir
     * @param int $duration
     * @return File
     * @throws \Exception
     */
    private function saveFileEntry(SymFile $file, string $dir, int $duration)
    {

        $fileEntry = new File();
        $fileEntry->setCreated(new \DateTime());
        $fileEntry->setDir($dir);
        $fileEntry->setMd5($this->generateFileHash($file->getPathname()));
        $fileEntry->setName($file->getFilename());
        $fileEntry->setLength($duration);

        $this->entityManager->persist($fileEntry);
        $this->entityManager->flush();

        return $fileEntry;
    }

    /**
     * @param File $file
     * @param string $fileTitle
     * @param User $user
     * @return UserFile
     * @throws \Exception
     */
    private function saveUserFileReference(File $file, string $fileTitle, User $user) {

        $userFile = new UserFile();
        $userFile->setCreated(new \DateTime());
        $userFile->setFile($file);
        $userFile->setScrybeStatus(UserFile::SCRYBE_STATUS_NOT_SCRYBED);
        $userFile->setUpdated(new \DateTime());
        $userFile->setUser($user);
        $userFile->setTitle($fileTitle);

        $this->entityManager->persist($userFile);
        $this->entityManager->flush();

        return $userFile;
    }
}

<?php


namespace App\Form;

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
        $newFileName = $fileMd5.$file->getExtension();

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
            $fileExistsInDb = $this->saveFileEntry($uploadedFile, $newFileDir);
        }

        $userFile = $this->saveUserFileReference($fileExistsInDb, $file->getClientOriginalName(), $this->user);

        return $userFile;
    }

    /*
    public function UploadFormFile(Request $request)
    {
        $uploadResult = new UploadResult();

        if ($request->files->has('uploadedFile')) {
            $fileOperator = new FileOperator();
            /** @var UploadedFile $uploadFile */
            /*$uploadFile = $request->files->get('uploadedFile');

            if ($uploadFile->getError() === 0) {
                $uploadFileMd5 = $fileOperator->generateFileHash($uploadFile->getPath());
                $existingFile = $fileOperator->searchFileByHash($uploadFileMd5);
                if ($existingFile && $existingFile->) {
                    $uploadResult->setUploadedFileName($existingFile->getFileName());
                    $uploadResult->setUploadSuccess(true);
                }
                else {
                    $new_path = $this->uploadFileToServer($uploadFile->getPath());

                }
                if ($new_path !== false) {
                    $uploadError = false;
                } else {
                    $uploadError = true;
                }
            } else {
                $uploadError = $uploadFile->getErrorMessage();
            }
        } else {
            $uploadError = "No file found";
        }

        return $uploadResult;
    }
    */
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
            $result = $file->move($newDir, $newName);//) {
//                $uploadedFile = new SymFile($this->basePath.$newDir.$newName);
//                return $uploadedFile;
//            }
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

    private function saveFileEntry(SymFile $file, string $dir)
    {

        $fileEntry = new File();
        $fileEntry->setCreated(new \DateTime());
        $fileEntry->setDir($dir);
        $fileEntry->setMd5($this->generateFileHash($file->getPathname()));
        $fileEntry->setName($file->getFilename());
        $fileEntry->setLength(9); //TODO reikia plugino iraso ilgiui skaiciuoti. Arba pries taidar net konvertuoti

        $this->entityManager->persist($fileEntry);
        $this->entityManager->flush();

        return $fileEntry;
    }

    private function saveUserFileReference(File $file, string $fileTitle, User $user) {

        $userFile = new UserFile();
        $userFile->setCreated(new \DateTime());
        $userFile->setFile($file);
        $userFile->setScrybeStatus(0);
        $userFile->setUpdated(new \DateTime());
        $userFile->setUser($user);
        $userFile->setTitle($fileTitle);

        $this->entityManager->persist($userFile);
        $this->entityManager->flush();

        return $userFile;
    }
}

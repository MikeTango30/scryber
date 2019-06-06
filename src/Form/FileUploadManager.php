<?php


namespace App\Form;

use App\Api\Tilde\Connector;
use App\Api\Tilde\RequestModel;
use App\Api\Tilde\ResponseModel;
use App\FileOperations\FileOperator;
use App\FileOperations\MediaConverter;
use App\Entity\File;
use App\Entity\User;
use App\Entity\UserFile;
use App\Pricing\CreditUpdates;
use App\ScribeFormats\CtmTransformer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File as SymFile;

class FileUploadManager
{
    protected $entityManager;
    private $user;

    public function __construct(User $user, EntityManagerInterface $entityManager)
    {
        $this->basePath = getcwd() . DIRECTORY_SEPARATOR;
        $this->user = $user;
        $this->entityManager = $entityManager;
    }


    /**
     * @param UploadedFile $file
     * @return UserFile
     * @throws \Exception
     */
    public function processUploadFile(UploadedFile $file)
    {
        $fileMd5 = $this->generateFileHash($file->getPathname());
        $newFileDir = '';
        $newFileName = $fileMd5 . '.mp4';//$file->getExtension();

        $fileOperator = new FileOperator();

        $fileExistsInDb = $this->searchFileByHash($fileMd5);
        $fileExistsInFileSystem = file_exists($fileOperator->getFileInternalPathUsingString(
        $newFileDir . $newFileName));

        if (!$fileExistsInFileSystem) {
            $uploadedFile = new SymFile($this->uploadFileToServer(
                $file,
                $this->basePath . $_ENV['AUDIO_FILES_UPLOAD_DIR'] . $newFileDir,
                $newFileName
            ));
        } else {
            $uploadedFile = new SymFile($fileOperator->getFileInternalPathUsingString($newFileDir . $newFileName));
        }
        if (!$fileExistsInDb) {
            $mediaConverter = new MediaConverter();
            $fileDuration = $mediaConverter->getMediaDuration($uploadedFile);
//            $fileDuration = 11;
            $fileExistsInDb = $this->saveFileEntry($uploadedFile, $newFileDir, $fileMd5, $fileDuration);
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
//            $mediaConverter = new MediaConverter();
//            $file = $mediaConverter->convertFile($file);
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
    private function saveFileEntry(SymFile $file, string $dir, string $fileMd5, int $duration)
    {

        $fileEntry = new File();
        $fileEntry->setCreated(new DateTime());
        $fileEntry->setDir($dir);
        $fileEntry->setMd5($fileMd5);
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
    private function saveUserFileReference(File $file, string $fileTitle, User $user)
    {

        $userFile = new UserFile();
        $userFile->setCreated(new DateTime());
        $userFile->setFile($file);
        $userFile->setScrybeStatus(UserFile::SCRYBE_STATUS_NOT_SCRYBED);
        $userFile->setUpdated(new DateTime());
        $userFile->setUser($user);
        $userFile->setTitle($fileTitle);

        $this->entityManager->persist($userFile);
        $this->entityManager->flush();

        return $userFile;
    }

    /**
     * @param File $file
     * @return ResponseModel
     */
    public function sendFileToScrybe(File $file) : ResponseModel
    {
        $connector = new Connector();
        $fileOperator = new FileOperator();
        $request = new RequestModel($fileOperator->getFileInternalPathUsingString($file->getDir() . $file->getName()));
        $response = $connector->sendFile($request);

        return $response;
    }

    /**
     * @param string $userfileId
     * @throws \Exception
     */
    public function saveScrybeResults(string $userfileId): void
    {
        /** @var UserFile $userFile */
        $userFile = $this->entityManager->getRepository(UserFile::class)->find($userfileId);

        if ($userFile) {
            $originalFile = $userFile->getFile();
            $connector = new Connector();

            $jobStatus = $connector->checkJobStatus($originalFile->getJobId());

            if ($jobStatus->getResponseStatus() == ResponseModel::SUCCESS) {
                $summary = $connector->getJobSummary($originalFile->getJobId());
                $text = $connector->getScrybedTxt($originalFile->getJobId());
                $ctm = $connector->getScrybedCtm($originalFile->getJobId());

                if (empty($originalFile->getDefaultCtm())) {
                    $originalFile->setDefaultCtm($ctm->getRawCtm());
                    $originalFile->setPlainText($text);
                    $originalFile->setWordsCount($summary->getWords());
                    $originalFile->setConfidence($summary->getConfidence());
                    $this->entityManager->persist($originalFile);
                }
                if ($userFile->getUser()->getId() == $this->user->getId() && empty($userFile->getText())) {
                    $ctmTransformer = new CtmTransformer();
                    $userFile->setText($ctmTransformer->getCtmJson($originalFile));
                    $userFile->setUpdated(new DateTime());
                    $userFile->setScrybeStatus(UserFile::SCRYBE_STATUS_COMPLETED);
                    $this->entityManager->persist($userFile);

                    $creditUpdater = new CreditUpdates($this->entityManager);
                    $creditUpdater->chageUserCreditTotal($this->user, $originalFile->getLength() * -1);
                    $creditUpdater->saveUserCreditChangeLog($this->user, $originalFile->getLength() * -1, $originalFile);
                }
            } elseif ($userFile->getUser()->getId() == $this->user->getId() && in_array($jobStatus->getResponseStatus(), [ResponseModel::NO_SPEECH, ResponseModel::DECODING_ERROR, ResponseModel::ERROR, ResponseModel::TYPE_NOT_RECOGNIZED])) {
                $userFile->setScrybeStatus(UserFile::SCRYBE_STATUS_SCRYBE_IMPOSIBLE);
                $userFile->setUpdated(new DateTime());
                $userFile->setText([]);
                $this->entityManager->persist($userFile);
            }

            $this->entityManager->flush();
        }
    }

    /**
     * @param UserFile $userfileId
     * @return array
     */
    public function processScrybeFile(UserFile $userfileId)
    {
        $response = ['error'=>''];

        /** @var User $user */
        $user = $this->user;

        /** @var UserFile $userFile */
        $userFile = $this->entityManager->getRepository(UserFile::class)->findOneBy(['id' => $userfileId, 'user' => $user]);

        if ($userFile) {
            $originalFile = $userFile->getFile();

            if ($user->getCredits() < $originalFile->getLength()) {
                $response['error'] = 'Klaida. Nepakanka kreditų';
            } elseif (empty($originalFile->getDefaultCtm())) {
                $result = $this->sendFileToScrybe($originalFile);

                $originalFile->setJobId($result->getRequestId());
                $userFile->setScrybeStatus(UserFile::SCRYBE_STATUS_IN_PROGRESS);

                $this->entityManager->flush();
            }
        }

        return $response;
    }
}
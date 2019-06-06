<?php


namespace App\FileOperations;


use App\Entity\File;

class FileOperator
{
    private $internalMediaDir;
    private $wwwMediaDir;

    public function __construct()
    {
        $this->internalMediaDir = getcwd() . DIRECTORY_SEPARATOR . $_ENV['AUDIO_FILES_UPLOAD_DIR'];
        $this->wwwMediaDir = $_ENV['AUDIO_FILES_UPLOAD_DIR'];
    }

    /**
     * @param File $file
     * @return string
     */
    public function getFileInternalPath(File $file) : string
    {
        $path = '';

        if ($file) {
            $path = $this->internalMediaDir.$file->getFilePathName();
        }

        return $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getFileInternalPathUsingString(string $path) : string
    {
        return $this->internalMediaDir.$path;
    }
}
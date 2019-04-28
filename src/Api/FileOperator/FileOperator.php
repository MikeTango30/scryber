<?php
/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-27
 * Time: 17:01
 */

namespace App\Api\FileOperator;


class FileOperator
{
    protected $basePath;

    public function __construct()
    {
        $this->basePath = getcwd().DIRECTORY_SEPARATOR;
    }

    public function UploadFileToServer(string $localPath, bool $isFormUpload = true) : string
    {
        if(file_exists($this->basePath.$localPath)) {
            $fileInfo = pathinfo($this->basePath.$localPath);
            $file_md5 = md5_file($localPath);
            $new_file_path = $this->basePath.$_ENV['AUDIO_FILES_UPLOAD_DIR'].$file_md5.'.'.$fileInfo['extension'];
            if(!file_exists($new_file_path)) {
                if($isFormUpload && move_uploaded_file($localPath, $new_file_path)) {
                    return $new_file_path;
                }
                elseif (copy($localPath, $new_file_path)) {
                    return $new_file_path;
                }
                else {
                    return false;
                }
            }
            return $new_file_path;

        }
        return false;
    }

}
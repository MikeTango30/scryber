<?php


namespace App\Api;

use Html5Video\Html5Video;


class FileConverter
{

    public function ConvertFile(string $srcVideo, string $targetVideo) : void
    {
        $config = array(
            'ffmpeg.bin' => '/usr/bin/ffmpeg',
            'qt-faststart.bin' => '/usr/bin/qt-faststart',
        );
        $html5 = new Html5Video($config);

// target format is the file extension of $targetVideo. One of mp4, webm, or ogg
        $profileName = '480p-hd';
        $html5->convert($srcVideo, $targetVideo, $profileName);
    }
}
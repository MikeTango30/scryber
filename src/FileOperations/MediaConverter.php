<?php


namespace App\FileOperations;


use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Filters\Video\ResizeFilter;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Video\X264;
use Symfony\Component\HttpFoundation\File\File;

class MediaConverter
{

    public function convertFile(File $srcMedia): File
    {
        $ffmpeg = FFMpeg::create();
        $media = $ffmpeg->open($srcMedia->getPathname());
        $videoStreamCount = $media->getStreams()->videos();
        $audioStreamCount = $media->getStreams()->audios();
        if (!$audioStreamCount->count()) {
            $convertResult = false;
        } elseif ($videoStreamCount->count()) {
            $format = new X264();
            $format->setAudioCodec('libmp3lame');
            $format->setKiloBitrate(200);
            $media
                ->filters()
                ->resize(new Dimension(320, 240), ResizeFilter::RESIZEMODE_INSET, true)
                ->addMetadata(['title' => 'demo_record.m4a', 'artiset' => 'Scryber'])
//        ->framerate(new \FFMpeg\Coordinate\FrameRate(15), 0)
                ->synchronize();
            $convertResult = true;
        } else {
            $format = new Mp3();
            $format->getAudioKiloBitrate(160);
            $convertResult = true;
        }

//        if ($convertResult) {
//            $targetMediaName = 'file_'.time().'_'.rand(10, 1000).'.mp4';
//            $targetMediaPathName = $srcMedia->getPath().DIRECTORY_SEPARATOR.$targetMediaName;
//            $media->save($format, $targetMediaPathName);
//
//            $convertedFile = new File($targetMediaPathName);
//            $srcMedia = $convertedFile;
//        }

        return $srcMedia;
    }

    /**
     * @param File $srcMedia
     * @return int
     */
    public function getMediaDuration(File $srcMedia) : int
    {
        $mediaFileInfo = $this->getMediaFileInfo($srcMedia);

        $duration = 0;

        if (!empty($mediaFileInfo)) {
            $duration = ceil($mediaFileInfo['duration']);
        }

        return $duration;
    }

    /**
     * @param File $srcMedia
     * @return array
     */
    private function getMediaFileInfo(File $srcMedia) : array
    {
        $ffprobe = FFProbe::create();
        return $ffprobe
            ->format($srcMedia->getPathname())// extracts file informations
//    ->get('duration'));             // returns the duration property
            ->all();

        /*
         * ["filename"]=>
          string(15) "demo_record.m4a"
          ["nb_streams"]=>
          int(1)
          ["nb_programs"]=>
          int(0)
          ["format_name"]=>
          string(23) "mov,mp4,m4a,3gp,3g2,mj2"
          ["format_long_name"]=>
          string(15) "QuickTime / MOV"
          ["start_time"]=>
          string(8) "0.000000"
          ["duration"]=>
          string(8) "7.957313"
          ["size"]=>
          string(6) "189526"
          ["bit_rate"]=>
          string(6) "190542"
          ["probe_score"]=>

         */

    }
}
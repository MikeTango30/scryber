<?php
/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-24
 * Time: 22:11
 */

namespace App\Api\Tilde;


class RequestMode
{
    const SKIP_SEGMENTATION_DIARIZATION = 'skip_diariz';
    const SPEAKERS = 'speakers';
    const CTM = 'ctm';
    const FULL = 'full';
    const SKIP_POSTPROCESS = 'skip_postprocess';
}
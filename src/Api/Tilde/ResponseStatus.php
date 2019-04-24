<?php
/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-24
 * Time: 22:40
 */

namespace App\Api\Tilde;


class ResponseStatus
{
    const SUCCESS = 0;
    const NO_SPEECH = 1;
    const DECODING_ERROR = 2;
    const IN_QUEUE = 3;
    const PROCESSING = 4;
    const TYPE_NOT_RECOGNIZED = 5;
    const RECOGNITION_TIMEOUT = 6;
    const TRY_LATER = 9;
    const ERROR = 10;

}
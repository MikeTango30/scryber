<?php
/**
 * Created by PhpStorm.
 * User: Alius.C
 * Date: 2019-04-27
 * Time: 20:37
 */

namespace App\Api\Tilde;

class CtmModel
{
    /** @var array */
    private $ctm;

    /** @var string */
    private $rawCtm;

    public function __construct(string $ctmRawContents)
    {
        $ctmArray = [];
        $ctmContentLines = explode("\n", $ctmRawContents);
        if(!empty($ctmContentLines)) {
            foreach ($ctmContentLines as $ctmContentLine) {
                if(!empty($ctmContentLine)) {
                    list($utterance, $channel, $beginTime, $duration, $word, $confidence) = explode(' ', $ctmContentLine);
                    $ctmArray[] = new CtmLine($utterance, $channel, $beginTime, $duration, $word, $confidence);
                }
            }
        }

        $this->ctm = $ctmArray;
        $this->rawCtm = $ctmRawContents;
    }

    /**
     * @return array
     */
    public function getCtm(): array
    {
        return $this->ctm;
    }

    /**
     * @return string
     */
    public function getRawCtm(): string
    {
        return $this->rawCtm;
    }
}
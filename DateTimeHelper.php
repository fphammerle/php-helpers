<?php

namespace fphammerle\helpers;

class DateTimeHelper
{
    /**
     * @param integer|null $timestamp unix timestamp
     * @return DateTime|null
     */
    public static function timestampToDateTime($timestamp)
    {
        if($timestamp === null) {
            return null;
        } else {
            $dt = new \DateTime();
            $dt->setTimestamp($timestamp);
            return $dt;
        }
    }
}

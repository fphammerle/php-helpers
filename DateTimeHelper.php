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
        } elseif(is_int($timestamp)) {
            $dt = new \DateTime();
            $dt->setTimestamp($timestamp);
            return $dt;
        } else {
            throw new \InvalidArgumentException('expected integer or null');
        }
    }
}

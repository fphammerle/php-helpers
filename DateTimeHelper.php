<?php

namespace fphammerle\helpers;

class DateTimeHelper
{
    /**
     * @param integer|null $timestamp unix timestamp
     * @return \DateTime|null
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

    /**
     * @param string|null $text
     * @return \DatePeriod|null
     */
    public static function parse($text)
    {
        if($text === null) {
            return null;
        } else {
            if(preg_match(
                    '/^(?P<y>\d{4})-(?P<m>\d{2})-(?P<d>\d{2})'
                        .'([ T](?P<h>\d{2}):(?P<i>\d{2}):(?P<s>\d{2}))?$/',
                    $text,
                    $attr
                    )) {
                $start = new \DateTime();
                $start->setDate($attr['y'], $attr['m'], $attr['d']);
                $start->setTime(
                    isset($attr['h']) ? $attr['h'] : 0,
                    isset($attr['i']) ? $attr['i'] : 0,
                    isset($attr['s']) ? $attr['s'] : 0
                    );
                if(isset($attr['h'])) {
                    $interval = new \DateInterval('PT1S');
                } else {
                    $interval = new \DateInterval('P1D');
                }
                $end = clone $start;
                $end->add($interval);
                return new \DatePeriod($start, $interval, $end);
            } else {
                throw new \InvalidArgumentException(
                    sprintf("could not parse string '%s'", $text)
                    );
            }
        }
    }

    /**
     * @param string|null $text
     * @return \DateTime|null
     */
    public static function parseGetStart($text)
    {
        $period = self::parse($text);
        if($period) {
            return $period->start;
        } else {
            return null;
        }
    }
}

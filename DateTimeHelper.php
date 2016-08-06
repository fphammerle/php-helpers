<?php

namespace fphammerle\helpers;

class DateTimeHelper
{
    const _timezone_iso_pattern = '(Z|[\+-]\d{2}.\d{2})';

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
            if(preg_match('/^\d{4}-(?P<m>\d{2})( ?' . self::_timezone_iso_pattern . ')?$/', $text, $attr)) {
                $start = new \DateTime($text);
                $interval = new \DateInterval('P1M');
                return new \DatePeriod($start, $interval, 0);
            } elseif(preg_match(
                    '/^(?P<y>\d{4})-(?P<m>\d{2})(-(?P<d>\d{2})'
                        .'([ T](?P<h>\d{2}):(?P<i>\d{2})(:(?P<s>\d{2}))?)?)?'
                        . '(' . self::_timezone_iso_pattern . ')?$/',
                    $text,
                    $attr
                    )) {
                $start = new \DateTime($text);
                if(!empty($attr['s'])) {
                    $interval = new \DateInterval('PT1S');
                } elseif(!empty($attr['i'])) {
                    $interval = new \DateInterval('PT1M');
                } else {
                    $interval = new \DateInterval('P1D');
                }
                return new \DatePeriod($start, $interval, 0);
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

    public static function deinvertInterval(\DateInterval $source = null)
    {
        // \DateInterval does not implement clone.
        // @see https://bugs.php.net/bug.php?id=50559
        $result = unserialize(serialize($source));
        if($result->invert) {
            $result->y *= -1;
            $result->m *= -1;
            $result->d *= -1;
            $result->h *= -1;
            $result->i *= -1;
            $result->s *= -1;
            $result->invert = 0;
        }
        return $result;
    }

    /**
     * @param \DateInterval|null $i
     * @return string|null
     */
    public static function intervalToIso(\DateInterval $i = null)
    {
        if(is_null($i)) {
            return null;
        } elseif(sizeof(get_object_vars($i)) == 0) {
            throw new \InvalidArgumentException(
                sprintf("given interval is invalid\n%s", print_r($i, true))
                );
        } else {
            $i = self::deinvertInterval($i);
            if($i->y < 0 || $i->m < 0 || $i->d < 0 || $i->h < 0 || $i->i < 0 || $i->s < 0) {
                throw new \Exception('negative intervals are not supported');
            } else {
                return $i->format('P%yY%mM%dDT%hH%iM%sS');
            }
        }
    }

    /**
     * @param \DatePeriod|null $p
     * @return string|null
     */
    public static function periodToIso(\DatePeriod $p = null)
    {
        if(is_null($p)) {
            return null;
        } else {
            // Cave:
            // (new \DatePeriod(
            //      new \DateTime('2016-08-05T14:50:14Z'),
            //      new \DateInterval('P1D'),
            //      -1
            //      )->recurrences == 1
            if($p->recurrences <= 0) {
                throw new \Exception(
                    'conversion of periods with number of occurances'
                      . ' being negative is not supported'
                    );
            }
            $repetitions = -1;
            foreach($p as $dt) {
                $repetitions++;
                // printf("%d. %s\n", $repetitions, $dt->format(\DateTime::ATOM));
            }
            // \DatePeriod::getStartDate() is available from php 5.6.5.
            $start_iso = $p->start->format(\DateTime::ATOM);
            // \DatePeriod::getDateInterval() is available from php 5.6.5.
            // \DatePeriod::$interval returned an invalid \DatePeriod instance
            // in php 7.0.8
            $interval_iso = self::intervalToIso(get_object_vars($p)['interval']);
            switch($repetitions) {
                case -1:
                    // no valid date within period
                    // e.g. new \DatePeriod(
                    //     new \DateTime('2016-08-05T14:50:14+08:00'),
                    //     new \DateInterval('PT1S'),
                    //     new \DateTime('2016-08-05T14:50:14+08:00')
                    //     )
                    throw new \InvalidArgumentException(
                        'given period does not contain any valid date'
                        );
                case 0:
                    return sprintf('%s/%s', $start_iso, $interval_iso);
                default:
                    return sprintf('R%d/%s/%s', $repetitions, $start_iso, $interval_iso);
            }
        }
    }
}

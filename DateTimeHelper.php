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
                    return sprintf(
                        '%s/%s',
                        $p->getStartDate()->format(\DateTime::ATOM),
                        self::intervalToIso($p->getDateInterval())
                        );
                default:
                    return sprintf(
                        'R%d/%s/%s',
                        $repetitions,
                        $p->getStartDate()->format(\DateTime::ATOM),
                        self::intervalToIso($p->getDateInterval())
                        );
            }
        }
    }
}

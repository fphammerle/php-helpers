<?php

namespace fphammerle\helpers\tests;

use fphammerle\helpers\DateTimeHelper;

class DateTimeHelperTest extends \PHPUnit_Framework_TestCase
{
    public function timestampToDateTimeProvider()
    {
        return [
            [null, null],
            [0, new \DateTime('1970-01-01 00:00:00', new \DateTimeZone('UTC'))],
            [0, new \DateTime('1970-01-01 01:00:00', new \DateTimeZone('Europe/Vienna'))],
            [1234567890, new \DateTime('2009-02-13 23:31:30', new \DateTimeZone('UTC'))],
            [1234567890, new \DateTime('2009-02-14 00:31:30', new \DateTimeZone('Europe/Vienna'))],
            [-3600, new \DateTime('1970-01-01 00:00:00', new \DateTimeZone('Europe/Vienna'))],
            ];
    }

    /**
     * @dataProvider timestampToDateTimeProvider
     */
    public function testTimestampToDateTime($timestamp, $expected_datetime)
    {
        $generated_datetime = DateTimeHelper::timestampToDateTime($timestamp);
        $this->assertEquals($expected_datetime, $generated_datetime);
    }

    public function timestampToDateTimeDefaultTimezoneProvider()
    {
        return [
            ['UTC', 100],
            ['Europe/Vienna', 0],
            ['Europe/Vienna', -100],
            ['Europe/Vienna', 100],
            ['Europe/London', 3600],
            ['US/Pacific', 3600],
            ];
    }

    /**
     * @dataProvider timestampToDateTimeDefaultTimezoneProvider
     */
    public function testTimestampToDateTimeDefaultTimezone($timezone, $timestamp)
    {
        date_default_timezone_set($timezone);
        $generated_datetime = DateTimeHelper::timestampToDateTime($timestamp);
        $this->assertSame($timestamp, $generated_datetime->getTimestamp());
    }

    public function parseProvider()
    {
        return [
            // null
            [null, 'UTC', null],
            [null, 'US/Pacific', null],
            // date
            ['2016-08-02', 'UTC', new \DatePeriod(
                new \DateTime('2016-08-02T00:00:00Z'),
                new \DateInterval('P1D'),
                new \DateTime('2016-08-03T00:00:00Z')
                )],
            ['2016-08-02', 'Europe/Vienna', new \DatePeriod(
                new \DateTime('2016-08-02T00:00:00+02:00'),
                new \DateInterval('P1D'),
                new \DateTime('2016-08-03T00:00:00+02:00')
                )],
            ['2016-08-02', 'Europe/Vienna', new \DatePeriod(
                new \DateTime('2016-08-01T22:00:00Z'),
                new \DateInterval('P1D'),
                new \DateTime('2016-08-02T22:00:00Z')
                )],
            ['2016-08-02+02:00', 'UTC', new \DatePeriod(
                 new \DateTime('2016-08-02T00:00:00+02:00'),
                 new \DateInterval('P1D'),
                 new \DateTime('2016-08-03T00:00:00+02:00')
                 )],
            ['2016-08-02+02:00', 'UTC', new \DatePeriod(
                 new \DateTime('2016-08-01T22:00:00Z'),
                 new \DateInterval('P1D'),
                 new \DateTime('2016-08-02T22:00:00Z')
                 )],
            // second
            ['2016-08-02 15:52:13', 'UTC', new \DatePeriod(
                 new \DateTime('2016-08-02T15:52:13Z'),
                 new \DateInterval('PT1S'),
                 new \DateTime('2016-08-02T15:52:14Z')
                 )],
            ['2016-08-02 15:52:13', 'Europe/Vienna', new \DatePeriod(
                 new \DateTime('2016-08-02T15:52:13+02:00'),
                 new \DateInterval('PT1S'),
                 new \DateTime('2016-08-02T15:52:14+02:00')
                 )],
            ['2016-08-02 15:52:13', 'Europe/Vienna', new \DatePeriod(
                 new \DateTime('2016-08-02T13:52:13Z'),
                 new \DateInterval('PT1S'),
                 new \DateTime('2016-08-02T13:52:14Z')
                 )],
            ['2016-08-02T15:52:13', 'US/Pacific', new \DatePeriod(
                 new \DateTime('2016-08-02T15:52:13-07:00'),
                 new \DateInterval('PT1S'),
                 new \DateTime('2016-08-02T15:52:14-07:00')
                 )],
            ['2016-08-02T15:52:13Z', 'US/Pacific', new \DatePeriod(
                 new \DateTime('2016-08-02T15:52:13Z'),
                 new \DateInterval('PT1S'),
                 new \DateTime('2016-08-02T15:52:14Z')
                 )],
            ['2016-08-02T15:52:13Z', 'Europe/Vienna', new \DatePeriod(
                 new \DateTime('2016-08-02T17:52:13+02:00'),
                 new \DateInterval('PT1S'),
                 new \DateTime('2016-08-02T14:52:14-01:00')
                 )],
            ['2016-08-02T15:52:13+00:00', 'Europe/Vienna', new \DatePeriod(
                 new \DateTime('2016-08-02T15:52:13Z'),
                 new \DateInterval('PT1S'),
                 new \DateTime('2016-08-02T15:52:14Z')
                 )],
            ['2016-08-02T15:52:13+02:00', 'US/Pacific', new \DatePeriod(
                 new \DateTime('2016-08-02T15:52:13+02:00'),
                 new \DateInterval('PT1S'),
                 new \DateTime('2016-08-02T15:52:14+02:00')
                 )],
            ['2016-08-02T15:52:13-08:00', 'UTC', new \DatePeriod(
                 new \DateTime('2016-08-02T23:52:13Z'),
                 new \DateInterval('PT1S'),
                 new \DateTime('2016-08-03T01:52:14+02:00')
                 )],
            ];
    }

    /**
     * @dataProvider parseProvider
     */
    public function testParse($text, $timezone, $expected)
    {
        date_default_timezone_set($timezone);
        $this->assertEquals($expected, DateTimeHelper::parse($text));
    }

    public function parseInvalidArgumentProvider()
    {
        return [
            ['     '],
            [''],
            ['2016--12'],
            ['2016-10-12 08:20#01'],
            [1],
            [false],
            ];
    }

    /**
     * @dataProvider parseInvalidArgumentProvider
     * @expectedException \InvalidArgumentException
     */
    public function testParseInvalidArgument($text)
    {
        DateTimeHelper::parse($text);
    }

    public function parseGetStartProvider()
    {
        return [
            [null, 'UTC', null],
            [null, 'US/Pacific', null],
            ['2016-08-02', 'UTC', new \DateTime('2016-08-02T00:00:00Z')],
            ['2016-08-02', 'Europe/Vienna', new \DateTime('2016-08-02T00:00:00+02:00')],
            ['2016-08-02', 'Europe/Vienna', new \DateTime('2016-08-01T22:00:00Z')],
            ['2016-08-02 15:52:13', 'UTC',  new \DateTime('2016-08-02T15:52:13Z')],
            ['2016-08-02 15:52:13', 'Europe/Vienna',  new \DateTime('2016-08-02T15:52:13+02:00')],
            ['2016-08-02 15:52:13', 'Europe/Vienna',  new \DateTime('2016-08-02T13:52:13Z')],
            ['2016-08-02T15:52:13', 'US/Pacific',  new \DateTime('2016-08-02T15:52:13-07:00')],
            ];
    }

    /**
     * @dataProvider parseGetStartProvider
     */
    public function testParseGetStart($text, $timezone, $expected)
    {
        date_default_timezone_set($timezone);
        $this->assertEquals($expected, DateTimeHelper::parseGetStart($text));
    }

    public function parseGetStartInvalidArgumentProvider()
    {
        return [
            ['     '],
            [''],
            ['2016--12'],
            ['2016-10-12 08:20#01'],
            [1],
            [false],
            ];
    }

    /**
     * @dataProvider parseGetStartInvalidArgumentProvider
     * @expectedException \InvalidArgumentException
     */
    public function testParseGetStartInvalidArgument($text)
    {
        DateTimeHelper::parseGetStart($text);
    }

    public function deinvertIntervalProvider()
    {
        return [
            [
                \DateInterval::createFromDateString('-2 years'),
                ['y' => -2, 'm' => 0, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                \DateInterval::createFromDateString('-2 months'),
                ['y' => 0, 'm' => -2, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                \DateInterval::createFromDateString('-2 days'),
                ['y' => 0, 'm' => 0, 'd' => -2, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                \DateInterval::createFromDateString('-2 hours'),
                ['y' => 0, 'm' => 0, 'd' => 0, 'h' => -2, 'i' => 0, 's' => 0],
                ],
            [
                \DateInterval::createFromDateString('-2 minutes'),
                ['y' => 0, 'm' => 0, 'd' => 0, 'h' => 0, 'i' => -2, 's' => 0],
                ],
            [
                \DateInterval::createFromDateString('-2 seconds'),
                ['y' => 0, 'm' => 0, 'd' => 0, 'h' => 0, 'i' => 0, 's' => -2],
                ],
            [
                (new \DateTime('2016-08'))->diff(new \DateTime('2016-07')),
                ['y' => 0, 'm' => -1, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new \DateTime('2016-08-03'))->diff(new \DateTime('2016-07-03')),
                ['y' => 0, 'm' => -1, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new \DateTime('2016-07-03'))->diff(new \DateTime('2016-08-03')),
                ['y' => 0, 'm' => 1, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new \DateTime('2016-08-04'))->diff(new \DateTime('2016-07-03')),
                ['y' => 0, 'm' => -1, 'd' => -1, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new \DateTime('2016-07-03'))->diff(new \DateTime('2016-08-04')),
                ['y' => 0, 'm' => 1, 'd' => 1, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new \DateTime('2016-08-02'))->diff(new \DateTime('2016-07-03')),
                ['y' => 0, 'm' => 0, 'd' => -30, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new \DateTime('2016-07-03'))->diff(new \DateTime('2016-08-02')),
                ['y' => 0, 'm' => 0, 'd' => 30, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new \DateTime('2016-08-04 18:10:02'))->diff(new \DateTime('2016-07-03 14:13:03')),
                ['y' => 0, 'm' => -1, 'd' => -1, 'h' => -3, 'i' => -56, 's' => -59],
                ],
            [
                (new \DateTime('2016-07-03 14:13:03'))->diff(new \DateTime('2016-08-04 18:10:02')),
                ['y' => 0, 'm' => 1, 'd' => 1, 'h' => 3, 'i' => 56, 's' => 59],
                ],
            ];
    }

    /**
     * @dataProvider deinvertIntervalProvider
     */
    public function testDeinvertInterval($source, $expected_attr)
    {
        // \DateInterval does not implement clone.
        // @see https://bugs.php.net/bug.php?id=50559
        $source_copy = unserialize(serialize($source));
        $deinverted = DateTimeHelper::deinvertInterval($source_copy);
        $this->assertEquals($source, $source_copy);
        $this->assertEquals(0, $deinverted->invert);
        foreach($expected_attr as $k => $v) {
            $this->assertSame($v, $deinverted->$k);
        }
    }

    public function intervalToIsoProvider()
    {
        return [
            [null, null],
            ];
    }

    /**
     * @dataProvider intervalToIsoProvider
     */
    public function testIntervalToIso($interval, $iso)
    {
        $this->assertSame($iso, DateTimeHelper::intervalToIso($interval));
    }

    public function intervalToIsoReinitProvider()
    {
        return [
            [new \DateInterval('P1Y')],
            [new \DateInterval('P1M')],
            [new \DateInterval('P1D')],
            [new \DateInterval('PT1H')],
            [new \DateInterval('PT1M')],
            [new \DateInterval('PT1S')],
            [new \DateInterval('P1Y2M3DT4H5M6S')],
            ];
    }

    /**
     * @dataProvider intervalToIsoReinitProvider
     */
    public function testIntervalToIsoReinit($interval)
    {
        $iso = DateTimeHelper::intervalToIso($interval);
        $this->assertEquals($interval, new \DateInterval($iso));
    }

    public function intervalToIsoReinitUnsupportedProvider()
    {
        return [
            [\DateInterval::createFromDateString('-2 years')],
            [\DateInterval::createFromDateString('-2 months')],
            [\DateInterval::createFromDateString('-2 days')],
            [\DateInterval::createFromDateString('-2 hours')],
            [\DateInterval::createFromDateString('-2 minutes')],
            [\DateInterval::createFromDateString('-2 seconds')],
            [(new \DateTime('2016-08-03'))->diff(new \DateTime('2016-07-03'))],
            [(new \DateTime('2016-08-03 10:00:01'))->diff(new \DateTime('2016-08-03 10:00:00'))],
            ];
    }

    /**
     * @dataProvider intervalToIsoReinitUnsupportedProvider
     * @expectedException \Exception
     */
    public function testIntervalToIsoReinitUnsupported($interval)
    {
        DateTimeHelper::intervalToIso($interval);
    }

    public function periodToIsoProvider()
    {
        return [
            [null, null],
            [
                new \DatePeriod(
                    new \DateTime('2016-08-05T14:50:14+08:00'),
                    new \DateInterval('P1D'),
                    new \DateTime('2016-08-10T14:50:14+08:00')
                    ),
                'R4/2016-08-05T14:50:14+08:00/P0Y0M1DT0H0M0S',
                ],
            [
                new \DatePeriod(
                    new \DateTime('2016-08-05T14:50:14+08:00'),
                    new \DateInterval('P5D'),
                    new \DateTime('2016-08-10T14:50:14+08:00')
                    ),
                '2016-08-05T14:50:14+08:00/P0Y0M5DT0H0M0S',
                ],
            [
                new \DatePeriod(
                    new \DateTime('2016-08-05T14:50:14+08:00'),
                    new \DateInterval('P1Y2M3DT4H5M6S'),
                    new \DateTime('2017-10-08T18:55:20+08:00')
                    ),
                '2016-08-05T14:50:14+08:00/P1Y2M3DT4H5M6S',
                ],
            [
                new \DatePeriod(
                    new \DateTime('2016-08-05T14:50:14Z'),
                    new \DateInterval('P1D'),
                    0
                    ),
                '2016-08-05T14:50:14+00:00/P0Y0M1DT0H0M0S',
                ],
            [
                new \DatePeriod(
                    new \DateTime('2016-08-05T14:50:14Z'),
                    new \DateInterval('PT5M'),
                    3
                    ),
                'R3/2016-08-05T14:50:14+00:00/P0Y0M0DT0H5M0S',
                ],
            [
                new \DatePeriod('R3/2016-08-05T14:50:14Z/PT5M'),
                'R3/2016-08-05T14:50:14+00:00/P0Y0M0DT0H5M0S',
                ],
            [
                new \DatePeriod('R4/2016-08-05T14:50:14Z/P1Y2M3DT4H5M6S'),
                'R4/2016-08-05T14:50:14+00:00/P1Y2M3DT4H5M6S',
                ],
            [
                DateTimeHelper::parse('2016-08-05T14:50:14Z'),
                '2016-08-05T14:50:14+00:00/P0Y0M0DT0H0M1S',
                ],
            [
                DateTimeHelper::parse('2016-08-05Z'),
                '2016-08-05T00:00:00+00:00/P0Y0M1DT0H0M0S',
                ],
            [
                DateTimeHelper::parse('2016-08-05-03:00'),
                '2016-08-05T00:00:00-03:00/P0Y0M1DT0H0M0S',
                ],
            ];
    }

    /**
     * @dataProvider periodToIsoProvider
     */
    public function testPeriodToIso($period, $iso)
    {
        $this->assertSame($iso, DateTimeHelper::periodToIso($period));
    }
}

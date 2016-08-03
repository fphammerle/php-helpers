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
            [null, 'UTC', null],
            [null, 'US/Pacific', null],
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
}

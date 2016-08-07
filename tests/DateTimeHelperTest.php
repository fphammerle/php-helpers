<?php

namespace fphammerle\helpers\tests;

use \DateInterval as DI;
use \DatePeriod as DP;
use \DateTime as DT;
use \fphammerle\helpers\DateTimeHelper;

class DateTimeHelperTest extends \PHPUnit_Framework_TestCase
{
    public function timestampToDateTimeProvider()
    {
        return [
            [null, null],
            [0, new DT('1970-01-01 00:00:00', new \DateTimeZone('UTC'))],
            [0, new DT('1970-01-01 01:00:00', new \DateTimeZone('Europe/Vienna'))],
            [1234567890, new DT('2009-02-13 23:31:30', new \DateTimeZone('UTC'))],
            [1234567890, new DT('2009-02-14 00:31:30', new \DateTimeZone('Europe/Vienna'))],
            [-3600, new DT('1970-01-01 00:00:00', new \DateTimeZone('Europe/Vienna'))],
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
            // year without timezone
            ['1900', 'UTC',           new DP(new DT('1900-01-01T00:00:00Z'),      new DI('P1Y'), 0)],
            ['0014', 'Europe/Vienna', new DP(new DT('0014-01-01T00:00:00+01:00'), new DI('P1Y'), 0)],
            ['2016', 'US/Pacific',    new DP(new DT('2016-01-01T00:00:00-08:00'), new DI('P1Y'), 0)],
            // year with timezone
            ['1900Z',       'US/Pacific',    new DP(new DT('1900-01-01T00:00:00Z'),      new DI('P1Y'), 0)],
            ['2016Z',       'Europe/Vienna', new DP(new DT('2016-01-01T00:00:00Z'),      new DI('P1Y'), 0)],
            ['2016+00:00',  'Europe/Vienna', new DP(new DT('2016-01-01T00:00:00Z'),      new DI('P1Y'), 0)],
            ['2016+02:00',  'US/Pacific',    new DP(new DT('2016-01-01T00:00:00+02:00'), new DI('P1Y'), 0)],
            ['0000 +02:05', 'US/Pacific',    new DP(new DT('0000-01-01T00:00:00+02:05'), new DI('P1Y'), 0)],
            ['2016-08:00',  'UTC',           new DP(new DT('2016-01-01T00:00:00-08:00'), new DI('P1Y'), 0)],
            ['2016 -08:00', 'UTC',           new DP(new DT('2016-01-01T00:00:00-08:00'), new DI('P1Y'), 0)],
            // month without timezone
            ['2016-08', 'UTC',           new DP(new DT('2016-08-01T00:00:00Z'),      new DI('P1M'), 0)],
            ['2016-08', 'Europe/Vienna', new DP(new DT('2016-08-01T00:00:00+02:00'), new DI('P1M'), 0)],
            ['2016-01', 'US/Pacific',    new DP(new DT('2016-01-01T00:00:00-08:00'), new DI('P1M'), 0)],
            // month with timezone
            ['2016-08Z',       'US/Pacific',    new DP(new DT('2016-08-01T00:00:00Z'),      new DI('P1M'), 0)],
            ['2016-08Z',       'Europe/Vienna', new DP(new DT('2016-08-01T00:00:00Z'),      new DI('P1M'), 0)],
            ['2016-01+00:00',  'Europe/Vienna', new DP(new DT('2016-01-01T00:00:00Z'),      new DI('P1M'), 0)],
            ['2016-01+02:00',  'US/Pacific',    new DP(new DT('2016-01-01T00:00:00+02:00'), new DI('P1M'), 0)],
            ['2016-01 +02:00', 'US/Pacific',    new DP(new DT('2016-01-01T00:00:00+02:00'), new DI('P1M'), 0)],
            ['2016-01 -08:00', 'UTC',           new DP(new DT('2016-01-01T00:00:00-08:00'), new DI('P1M'), 0)],
            // date without timezone
            ['2016-08-02', 'UTC',           new DP(new DT('2016-08-02T00:00:00Z'),      new DI('P1D'), 0)],
            ['2016-08-02', 'Europe/Vienna', new DP(new DT('2016-08-02T00:00:00+02:00'), new DI('P1D'), 0)],
            ['2016-01-02', 'US/Pacific',    new DP(new DT('2016-01-02T00:00:00-08:00'), new DI('P1D'), 0)],
            // date with timezone
            ['2016-08-02Z',      'US/Pacific',    new DP(new DT('2016-08-02T00:00:00Z'),      new DI('P1D'), 0)],
            ['2016-08-02Z',      'Europe/Vienna', new DP(new DT('2016-08-02T00:00:00Z'),      new DI('P1D'), 0)],
            ['2016-01-02+00:00', 'Europe/Vienna', new DP(new DT('2016-01-02T00:00:00Z'),      new DI('P1D'), 0)],
            ['2016-01-02+02:00', 'US/Pacific',    new DP(new DT('2016-01-02T00:00:00+02:00'), new DI('P1D'), 0)],
            ['2016-01-02-08:13', 'UTC',           new DP(new DT('2016-01-02T00:00:00-08:13'), new DI('P1D'), 0)],
            // minute without timezone
            ['2016-08-02 15:52', 'UTC',           new DP(new DT('2016-08-02T15:52:00Z'),      new DI('PT1M'), 0)],
            ['2016-08-02T15:52', 'UTC',           new DP(new DT('2016-08-02T15:52:00Z'),      new DI('PT1M'), 0)],
            ['2016-08-02T15:52', 'Europe/Vienna', new DP(new DT('2016-08-02T15:52:00+02:00'), new DI('PT1M'), 0)],
            ['2016-01-02T15:52', 'US/Pacific',    new DP(new DT('2016-01-02T15:52:00-08:00'), new DI('PT1M'), 0)],
            // minute with timezone
            ['2016-08-02 15:52Z',      'US/Pacific',    new DP(new DT('2016-08-02T15:52:00Z'),      new DI('PT1M'), 0)],
            ['2016-08-02T15:52Z',      'Europe/Vienna', new DP(new DT('2016-08-02T15:52:00Z'),      new DI('PT1M'), 0)],
            ['2016-01-02T15:52+00:00', 'Europe/Vienna', new DP(new DT('2016-01-02T15:52:00Z'),      new DI('PT1M'), 0)],
            ['2016-01-02T15:52+02:00', 'US/Pacific',    new DP(new DT('2016-01-02T15:52:00+02:00'), new DI('PT1M'), 0)],
            ['2016-01-02T15:52-08:00', 'UTC',           new DP(new DT('2016-01-02T15:52:00-08:00'), new DI('PT1M'), 0)],
            // second without timezone
            ['2016-08-02 15:52:13', 'UTC',           new DP(new DT('2016-08-02T15:52:13Z'),      new DI('PT1S'), 0)],
            ['2016-08-02T15:52:13', 'UTC',           new DP(new DT('2016-08-02T15:52:13Z'),      new DI('PT1S'), 0)],
            ['2016-08-02T15:52:13', 'Europe/Vienna', new DP(new DT('2016-08-02T15:52:13+02:00'), new DI('PT1S'), 0)],
            ['2016-01-02T15:52:00', 'US/Pacific',    new DP(new DT('2016-01-02T15:52:00-08:00'), new DI('PT1S'), 0)],
            // second with timezone
            ['2016-08-02 15:52:13Z',      'US/Pacific',    new DP(new DT('2016-08-02T15:52:13Z'),      new DI('PT1S'), 0)],
            ['2016-08-02T15:52:13Z',      'Europe/Vienna', new DP(new DT('2016-08-02T15:52:13Z'),      new DI('PT1S'), 0)],
            ['2016-01-02T15:52:13+00:00', 'Europe/Vienna', new DP(new DT('2016-01-02T15:52:13Z'),      new DI('PT1S'), 0)],
            ['2016-01-02T15:52:13+02:00', 'US/Pacific',    new DP(new DT('2016-01-02T15:52:13+02:00'), new DI('PT1S'), 0)],
            ['2016-01-02T15:52:13-08:00', 'UTC',           new DP(new DT('2016-01-02T15:52:13-08:00'), new DI('PT1S'), 0)],
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
            ['2016-01-08:00'],
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
            ['1900',                      'UTC',           new DT('1900-01-01T00:00:00Z')     ],
            ['0014',                      'Europe/Vienna', new DT('0014-01-01T00:00:00+01:00')],
            ['2016',                      'US/Pacific',    new DT('2016-01-01T00:00:00-08:00')],
            ['1900Z',                     'US/Pacific',    new DT('1900-01-01T00:00:00Z')     ],
            ['2016Z',                     'Europe/Vienna', new DT('2016-01-01T00:00:00Z')     ],
            ['2016+00:00',                'Europe/Vienna', new DT('2016-01-01T00:00:00Z')     ],
            ['2016+02:00',                'US/Pacific',    new DT('2016-01-01T00:00:00+02:00')],
            ['0000 +02:05',               'US/Pacific',    new DT('0000-01-01T00:00:00+02:05')],
            ['2016-08:00',                'UTC',           new DT('2016-01-01T00:00:00-08:00')],
            ['2016 -08:00',               'UTC',           new DT('2016-01-01T00:00:00-08:00')],
            ['2016-08',                   'UTC',           new DT('2016-08-01T00:00:00Z')     ],
            ['2016-08',                   'Europe/Vienna', new DT('2016-08-01T00:00:00+02:00')],
            ['2016-01',                   'US/Pacific',    new DT('2016-01-01T00:00:00-08:00')],
            ['2016-08Z',                  'US/Pacific',    new DT('2016-08-01T00:00:00Z')     ],
            ['2016-08Z',                  'Europe/Vienna', new DT('2016-08-01T00:00:00Z')     ],
            ['2016-01+00:00',             'Europe/Vienna', new DT('2016-01-01T00:00:00Z')     ],
            ['2016-01+02:00',             'US/Pacific',    new DT('2016-01-01T00:00:00+02:00')],
            ['2016-01 +02:00',            'US/Pacific',    new DT('2016-01-01T00:00:00+02:00')],
            ['2016-01 -08:00',            'UTC',           new DT('2016-01-01T00:00:00-08:00')],
            ['2016-08-02',                'UTC',           new DT('2016-08-02T00:00:00Z')     ],
            ['2016-08-02',                'Europe/Vienna', new DT('2016-08-02T00:00:00+02:00')],
            ['2016-01-02',                'US/Pacific',    new DT('2016-01-02T00:00:00-08:00')],
            ['2016-08-02Z',               'US/Pacific',    new DT('2016-08-02T00:00:00Z')     ],
            ['2016-08-02Z',               'Europe/Vienna', new DT('2016-08-02T00:00:00Z')     ],
            ['2016-01-02+00:00',          'Europe/Vienna', new DT('2016-01-02T00:00:00Z')     ],
            ['2016-01-02+02:00',          'US/Pacific',    new DT('2016-01-02T00:00:00+02:00')],
            ['2016-01-02-08:13',          'UTC',           new DT('2016-01-02T00:00:00-08:13')],
            ['2016-08-02 15:52',          'UTC',           new DT('2016-08-02T15:52:00Z')     ],
            ['2016-08-02T15:52',          'UTC',           new DT('2016-08-02T15:52:00Z')     ],
            ['2016-08-02T15:52',          'Europe/Vienna', new DT('2016-08-02T15:52:00+02:00')],
            ['2016-01-02T15:52',          'US/Pacific',    new DT('2016-01-02T15:52:00-08:00')],
            ['2016-08-02 15:52Z',         'US/Pacific',    new DT('2016-08-02T15:52:00Z')     ],
            ['2016-08-02T15:52Z',         'Europe/Vienna', new DT('2016-08-02T15:52:00Z')     ],
            ['2016-01-02T15:52+00:00',    'Europe/Vienna', new DT('2016-01-02T15:52:00Z')     ],
            ['2016-01-02T15:52+02:00',    'US/Pacific',    new DT('2016-01-02T15:52:00+02:00')],
            ['2016-01-02T15:52-08:00',    'UTC',           new DT('2016-01-02T15:52:00-08:00')],
            ['2016-08-02 15:52:13',       'UTC',           new DT('2016-08-02T15:52:13Z')     ],
            ['2016-08-02T15:52:13',       'UTC',           new DT('2016-08-02T15:52:13Z')     ],
            ['2016-08-02T15:52:13',       'Europe/Vienna', new DT('2016-08-02T15:52:13+02:00')],
            ['2016-01-02T15:52:00',       'US/Pacific',    new DT('2016-01-02T15:52:00-08:00')],
            ['2016-08-02 15:52:13Z',      'US/Pacific',    new DT('2016-08-02T15:52:13Z')     ],
            ['2016-08-02T15:52:13Z',      'Europe/Vienna', new DT('2016-08-02T15:52:13Z')     ],
            ['2016-01-02T15:52:13+00:00', 'Europe/Vienna', new DT('2016-01-02T15:52:13Z')     ],
            ['2016-01-02T15:52:13+02:00', 'US/Pacific',    new DT('2016-01-02T15:52:13+02:00')],
            ['2016-01-02T15:52:13-08:00', 'UTC',           new DT('2016-01-02T15:52:13-08:00')],
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
            ['2016-01-08:00'],
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
                DI::createFromDateString('-2 years'),
                ['y' => -2, 'm' => 0, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                DI::createFromDateString('-2 months'),
                ['y' => 0, 'm' => -2, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                DI::createFromDateString('-2 days'),
                ['y' => 0, 'm' => 0, 'd' => -2, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                DI::createFromDateString('-2 hours'),
                ['y' => 0, 'm' => 0, 'd' => 0, 'h' => -2, 'i' => 0, 's' => 0],
                ],
            [
                DI::createFromDateString('-2 minutes'),
                ['y' => 0, 'm' => 0, 'd' => 0, 'h' => 0, 'i' => -2, 's' => 0],
                ],
            [
                DI::createFromDateString('-2 seconds'),
                ['y' => 0, 'm' => 0, 'd' => 0, 'h' => 0, 'i' => 0, 's' => -2],
                ],
            [
                (new DT('2016-08'))->diff(new DT('2016-07')),
                ['y' => 0, 'm' => -1, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new DT('2016-08-03'))->diff(new DT('2016-07-03')),
                ['y' => 0, 'm' => -1, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new DT('2016-07-03'))->diff(new DT('2016-08-03')),
                ['y' => 0, 'm' => 1, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new DT('2016-08-04'))->diff(new DT('2016-07-03')),
                ['y' => 0, 'm' => -1, 'd' => -1, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new DT('2016-07-03'))->diff(new DT('2016-08-04')),
                ['y' => 0, 'm' => 1, 'd' => 1, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new DT('2016-08-02'))->diff(new DT('2016-07-03')),
                ['y' => 0, 'm' => 0, 'd' => -30, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new DT('2016-07-03'))->diff(new DT('2016-08-02')),
                ['y' => 0, 'm' => 0, 'd' => 30, 'h' => 0, 'i' => 0, 's' => 0],
                ],
            [
                (new DT('2016-08-04 18:10:02'))->diff(new DT('2016-07-03 14:13:03')),
                ['y' => 0, 'm' => -1, 'd' => -1, 'h' => -3, 'i' => -56, 's' => -59],
                ],
            [
                (new DT('2016-07-03 14:13:03'))->diff(new DT('2016-08-04 18:10:02')),
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

    public function isoProvider()
    {
        return [
            [null, null],
            [new DT('2016-09-16 21:13+02:00'), '2016-09-16T21:13:00+02:00'],
            // \DateInterval::__construct() does not support negative specifiers.
            // e.g. 'P-1Y'
            // 0 years
            [new DI('P0Y0M0DT0H0M0S'), 'P0S'           ],
            [new DI('P0Y0M0DT0H0M6S'), 'PT6S'          ],
            [new DI('P0Y0M0DT0H5M0S'), 'PT5M'          ],
            [new DI('P0Y0M0DT0H5M6S'), 'PT5M6S'        ],
            [new DI('P0Y0M0DT4H0M0S'), 'PT4H'          ],
            [new DI('P0Y0M3DT0H0M0S'), 'P3D'           ],
            [new DI('P0Y0M3DT0H0M6S'), 'P3DT6S'        ],
            [new DI('P0Y0M3DT4H5M6S'), 'P3DT4H5M6S'    ],
            [new DI('P0Y2M0DT0H0M0S'), 'P2M'           ],
            [new DI('P0Y2M0DT0H0M6S'), 'P2MT6S'        ],
            [new DI('P0Y2M3DT0H0M6S'), 'P2M3DT6S'      ],
            // >0 years, 0 months
            [new DI('P1Y0M0DT0H0M0S'), 'P1Y'           ],
            [new DI('P1Y0M0DT0H0M6S'), 'P1YT6S'        ],
            [new DI('P1Y0M0DT0H5M0S'), 'P1YT5M'        ],
            [new DI('P1Y0M0DT0H5M6S'), 'P1YT5M6S'      ],
            [new DI('P1Y0M0DT4H0M0S'), 'P1YT4H'        ],
            [new DI('P1Y0M3DT0H0M0S'), 'P1Y3D'         ],
            [new DI('P1Y0M3DT0H0M6S'), 'P1Y3DT6S'      ],
            [new DI('P1Y0M3DT4H5M6S'), 'P1Y3DT4H5M6S'  ],
            // >0 years, >0 months, 0 days (complete)
            [new DI('P1Y2M0DT0H0M0S'), 'P1Y2M'         ],
            [new DI('P1Y2M0DT0H0M6S'), 'P1Y2MT6S'      ],
            [new DI('P1Y2M0DT0H5M0S'), 'P1Y2MT5M'      ],
            [new DI('P1Y2M0DT0H5M6S'), 'P1Y2MT5M6S'    ],
            [new DI('P1Y2M0DT4H0M0S'), 'P1Y2MT4H'      ],
            [new DI('P1Y2M0DT4H0M6S'), 'P1Y2MT4H6S'    ],
            [new DI('P1Y2M0DT4H5M0S'), 'P1Y2MT4H5M'    ],
            [new DI('P1Y2M0DT4H5M6S'), 'P1Y2MT4H5M6S'  ],
            // >0 years, >0 months, >0 days (complete)
            [new DI('P1Y2M3DT0H0M0S'), 'P1Y2M3D'       ],
            [new DI('P1Y2M3DT0H0M6S'), 'P1Y2M3DT6S'    ],
            [new DI('P1Y2M3DT0H5M0S'), 'P1Y2M3DT5M'    ],
            [new DI('P1Y2M3DT0H5M6S'), 'P1Y2M3DT5M6S'  ],
            [new DI('P1Y2M3DT4H0M0S'), 'P1Y2M3DT4H'    ],
            [new DI('P1Y2M3DT4H0M6S'), 'P1Y2M3DT4H6S'  ],
            [new DI('P1Y2M3DT4H5M0S'), 'P1Y2M3DT4H5M'  ],
            [new DI('P1Y2M3DT4H5M6S'), 'P1Y2M3DT4H5M6S'],
            [
                new DP(
                    new DT('2016-08-05T14:50:14+08:00'),
                    new DI('P1D'),
                    new DT('2016-08-10T14:50:14+08:00')
                    ),
                'R4/2016-08-05T14:50:14+08:00/P1D',
                ],
            [
                new DP(
                    new DT('2016-08-05T14:50:14+08:00'),
                    new DI('P5D'),
                    new DT('2016-08-10T14:50:14+08:00')
                    ),
                '2016-08-05T14:50:14+08:00/P5D',
                ],
            [
                new DP(
                    new DT('2016-08-05T14:50:14+08:00'),
                    new DI('P1Y2M3DT4H5M6S'),
                    new DT('2017-10-08T18:55:20+08:00')
                    ),
                '2016-08-05T14:50:14+08:00/P1Y2M3DT4H5M6S',
                ],
            [
                new DP(
                    new DT('2016-08-05T14:50:14Z'),
                    new DI('P1D'),
                    0
                    ),
                '2016-08-05T14:50:14+00:00/P1D',
                ],
            [
                new DP(
                    new DT('2016-08-05T14:50:14Z'),
                    new DI('PT5M'),
                    3
                    ),
                'R3/2016-08-05T14:50:14+00:00/PT5M',
                ],
            [
                new DP('R3/2016-08-05T14:50:14Z/PT5M'),
                'R3/2016-08-05T14:50:14+00:00/PT5M',
                ],
            [
                new DP('R4/2016-08-05T14:50:14Z/P1Y2M3DT4H5M6S'),
                'R4/2016-08-05T14:50:14+00:00/P1Y2M3DT4H5M6S',
                ],
            [
                DateTimeHelper::parse('2016-08-05T14:50:14Z'),
                '2016-08-05T14:50:14+00:00/PT1S',
                ],
            [
                DateTimeHelper::parse('2016-08-05Z'),
                '2016-08-05T00:00:00+00:00/P1D',
                ],
            [
                DateTimeHelper::parse('2016-08-05-03:00'),
                '2016-08-05T00:00:00-03:00/P1D',
                ],
            ];
    }

    /**
     * @dataProvider isoProvider
     */
    public function testIso($interval, $iso)
    {
        $this->assertSame($iso, DateTimeHelper::iso($interval));
    }

    public function isoReinitProvider()
    {
        return [
            [new DT('2016-09-16')],
            [new DT('2016-09-16 +02:00')],
            [new DT('2016-09-16 -07:13')],
            [new DT('2016-09-16 21:13')],
            [new DT('2016-09-16 21:13+02:00')],
            [new DT('2016-09-16 14:13-07:00')],
            [new DI('P1Y')],
            [new DI('P1M')],
            [new DI('P1D')],
            [new DI('PT1H')],
            [new DI('PT1M')],
            [new DI('PT1S')],
            [new DI('P3DT1S')],
            [new DI('P1Y2M3DT4H5M6S')],
            ];
    }

    /**
     * @dataProvider isoReinitProvider
     */
    public function testIsoReinit($obj)
    {
        $iso = DateTimeHelper::iso($obj);
        $class = get_class($obj);
        $this->assertEquals($obj, new $class($iso));
    }

    public function isoUnsupportedProvider()
    {
        return [
            [DI::createFromDateString('-2 years')],
            [DI::createFromDateString('-2 months')],
            [DI::createFromDateString('-2 days')],
            [DI::createFromDateString('-2 hours')],
            [DI::createFromDateString('-2 minutes')],
            [DI::createFromDateString('-2 seconds')],
            [(new DT('2016-08-03'))->diff(new DT('2016-07-03'))],
            [(new DT('2016-08-03 10:00:01'))->diff(new DT('2016-08-03 10:00:00'))],
            [new DP(new DT('2016-08-05'), new DI('P1D'), new DT('2016-08-05'))],
            [new DP(new DT('2016-08-05'), new DI('PT1S'), new DT('2016-08-04'))],
            [new \Exception('unsupported class')],
            ];
    }

    /**
     * @dataProvider isoUnsupportedProvider
     * @expectedException \InvalidArgumentException
     */
    public function testIsoUnsupported($interval)
    {
        DateTimeHelper::iso($interval);
    }
}

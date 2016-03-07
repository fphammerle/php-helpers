<?php

namespace fphammerle\helpers\tests;

use fphammerle\helpers\DateTimeHelper;

class DateTimeHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testTimestampToDateTimeZero()
    {
        $this->assertEquals(
            0,
            DateTimeHelper::timestampToDateTime(0)->getTimestamp()
            );
    }

    public function testTimestampToDateTimeCompareUTC()
    {
        $created = DateTimeHelper::timestampToDateTime(1234567890);
        $expected = new \DateTime('2009-02-13 23:31:30', new \DateTimeZone('UTC'));
        $this->assertEquals($created->getTimestamp(), $expected->getTimestamp());
    }

    public function testTimestampToDateTimeCompareLocal()
    {
        $created = DateTimeHelper::timestampToDateTime(1234567890);
        $expected = new \DateTime('2009-02-14 00:31:30', new \DateTimeZone('Europe/Vienna'));
        $this->assertEquals($created->getTimestamp(), $expected->getTimestamp());
    }

    public function testTimestampToDateTimeSetLocal()
    {
        date_default_timezone_set('Europe/Vienna');
        $this->assertEquals(
            DateTimeHelper::timestampToDateTime(123456)->getTimestamp(),
            123456
            );
    }

    public function testTimestampToDateTimeSetUTC()
    {
        date_default_timezone_set('UTC');
        $this->assertEquals(
            DateTimeHelper::timestampToDateTime(123456)->getTimestamp(),
            123456
            );
    }

    public function testTimestampToDateTimeNull()
    {
        $this->assertEquals(
            DateTimeHelper::timestampToDateTime(null),
            null
            );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTimestampToDateInvalidArgumentFloat()
    {
        DateTimeHelper::timestampToDateTime(1.23);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTimestampToDateInvalidArgumentString()
    {
        DateTimeHelper::timestampToDateTime('');
    }
}

<?php

namespace fphammerle\helpers\tests;

use fphammerle\helpers\DateTimeHelper;

class DateTimeHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testTimestampToDateTime1()
    {
        $this->assertEquals(
            0,
            DateTimeHelper::timestampToDateTime(0)->getTimestamp()
            );
    }

    public function testTimestampToDateTime2()
    {
        $created = DateTimeHelper::timestampToDateTime(1234567890);
        $expected = new \DateTime('2009-02-13 23:31:30', new \DateTimeZone('UTC'));
        $this->assertEquals($created->getTimestamp(), $expected->getTimestamp());
    }

    public function testTimestampToDateTime3()
    {
        $created = DateTimeHelper::timestampToDateTime(1234567890);
        $expected = new \DateTime('2009-02-14 00:31:30', new \DateTimeZone('Europe/Vienna'));
        $this->assertEquals($created->getTimestamp(), $expected->getTimestamp());
    }

    public function testTimestampToDateTime4()
    {
        date_default_timezone_set('Europe/Vienna');
        $this->assertEquals(
            123456,
            DateTimeHelper::timestampToDateTime(123456)->getTimestamp()
            );
    }

    public function testTimestampToDateTime5()
    {
        date_default_timezone_set('UTC');
        $this->assertEquals(
            123456,
            DateTimeHelper::timestampToDateTime(123456)->getTimestamp()
            );
    }
}

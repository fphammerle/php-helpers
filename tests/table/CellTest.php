<?php

namespace fphammerle\helpers\tests\table;

use \fphammerle\helpers\table\Cell;

class CellTest extends \PHPUnit_Framework_TestCase
{
    public function setValueProvider()
    {
        return [
            [1],
            [1.23],
            ['string'],
            [true],
            ];
    }

    /**
     * @dataProvider setValueProvider
     */
    public function testSetValue($v)
    {
        $c = new Cell;
        $c->setValue($v);
        $this->assertSame($c->value, $v);
    }

    /**
     * @dataProvider setValueProvider
     */
    public function testConstruct($v)
    {
        $this->assertSame((new Cell($v))->value, $v);
    }

    public function testConstructDefault()
    {
        $this->assertNull((new Cell)->value);
    }

    public function toCSVProvider()
    {
        return [
            [0, '0'],
            [1, '1'],
            [1.23, '1.23'],
            [true, '1'],
            [false, '0'],
            [null, ''],
            ['', ''],
            ['string', 'string'],
            ['str"ing', '"str""ing"'],
            ['str"ing"', '"str""ing"""'],
            ['"string"', '"""string"""'],
            ['str,ing', '"str,ing"'],
            ['str,ing,', '"str,ing,"'],
            [',string,', '",string,"'],
            ["str\ning", "\"str\ning\""],
            ["str\ning\n", "\"str\ning\n\""],
            ["string\n", "\"string\n\""],
            ["\nstring", "\"\nstring\""],
            ["str\ring", "\"str\ring\""],
            ["str\r\ning", "\"str\r\ning\""],
            ["str\rin\ng", "\"str\rin\ng\""],
            ];
    }

    /**
     * @dataProvider toCSVProvider
     */
    public function testToCSV($v, $csv)
    {
        $c = new Cell($v);
        $this->assertSame($c->toCSV(), $csv);
    }

    public function toCSVDelimiterProvider()
    {
        return [
            [1, "\t", "1"],
            ["1\t2", "\t", "\"1\t2\""],
            ["1\t2", "\t", "\"1\t2\""],
            ["12\t", "\t", "\"12\t\""],
            ['a#$b', '#$', '"a#$b"'],
            ['a#$$b', '#$', '"a#$$b"'],
            ['1.23', '.', '"1.23"'],
            ];
    }

    /**
     * @dataProvider toCSVDelimiterProvider
     */
    public function testToCSVDelimiter($v, $d, $csv)
    {
        $c = new Cell($v);
        $this->assertSame($c->toCSV($d), $csv);
    }

    public function toCSVQuotesProvider()
    {
        return [
            [1, '*', '1'],
            ['1*2', '*', '*1**2*'],
            ['12*', '*', '*12***'],
            ['1"2"', '*', '1"2"'],
            ['1*"2"', '*', '*1**"2"*'],
            ['', '*', ''],
            ];
    }

    /**
     * @dataProvider toCSVQuotesProvider
     */
    public function testToCSVQuotes($v, $q, $csv)
    {
        $c = new Cell($v);
        $this->assertSame($c->toCSV(',', $q), $csv);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testToCsvDelimiterEqualsQuotes()
    {
        $c = new Cell;
        $c->toCSV('*', '*');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testToCsvDelimiterEmpty()
    {
        $c = new Cell;
        $c->toCSV('', '*');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testToCsvQuotesEmpty()
    {
        $c = new Cell;
        $c->toCSV('*', '');
    }
}

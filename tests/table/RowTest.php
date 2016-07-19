<?php

namespace fphammerle\helpers\tests\table;

use \fphammerle\helpers\table\Row;

class RowTest extends \PHPUnit_Framework_TestCase
{
    public function setCellValueProvider()
    {
        return [
            [0, 1],
            [0, 1.23],
            [0, 'string'],
            [0, true],
            [3, 1],
            [3, 1.23],
            [3, 'string'],
            [3, true],
            ];
    }

    /**
     * @dataProvider setCellValueProvider
     */
    public function testSetCellValue($c, $v)
    {
        $r = new Row;
        $r->setCellValue($c, $v);
        $this->assertSame($v, $r->getCell($c)->value);
    }

    public function setCellValueMultipleProvider()
    {
        return [
            [[[0, 'c0']]],
            [[[0, 'c0'], [1, 'c1']]],
            [[[0, 'c0'], [1, 'c1'], [3, 'c3']]],
            [[[2, 'c2'], [3, 'c3']]],
            ];
    }

    /**
     * @dataProvider setCellValueMultipleProvider
     */
    public function testSetCellMultipleValue($vv)
    {
        $r = new Row;
        foreach($vv as $cv) {
            $r->setCellValue($cv[0], $cv[1]);
        }
        foreach($vv as $cv) {
            $this->assertSame($cv[1], $r->getCell($cv[0])->value);
        }
    }

    public function constructProvider()
    {
        return [
            [[]],
            [['c0']],
            [['c0', 'c1']],
            [['c0', 'c1', 'c2']],
            ];
    }

    /**
     * @dataProvider constructProvider
     */
    public function testConstruct($vv)
    {
        $r = new Row($vv);
        foreach($vv as $c => $v) {
            $this->assertSame($v, $r->getCell($c)->value);
        }
    }

    public function getColumnsCountProvider()
    {
        return [
            [[], 0],
            [[[0, 'c0']], 1],
            [[[0, 'c0'], [1, 'c1']], 2],
            [[[0, 'c0'], [1, 'c1'], [3, 'c3']], 4],
            [[[5, 'c5']], 6],
            ];
    }

    /**
     * @dataProvider getColumnsCountProvider
     */
    public function testGetColumnsCountValue($vv, $count)
    {
        $r = new Row;
        foreach($vv as $cv) {
            $r->setCellValue($cv[0], $cv[1]);
        }
        $this->assertSame($count, $r->columnsCount);
    }

    public function toCSVProvider()
    {
        return [
            [[], "\r\n"],
            [[[0, 'c0']], "c0\r\n"],
            [[[0, 'c0'], [1, 'c1']], "c0,c1\r\n"],
            [[[0, 'c0'], [1, 'c1'], [3, 'c3']], "c0,c1,,c3\r\n"],
            [[[2, 'c2'], [3, 'c3']], ",,c2,c3\r\n"],
            [[[2, 'c,2'], [3, 'c3']], ",,\"c,2\",c3\r\n"],
            [[[2, "c2\n"], [3, 'c"3"']], ",,\"c2\n\",\"c\"\"3\"\"\"\r\n"],
            ];
    }

    /**
     * @dataProvider toCSVProvider
     */
    public function testToCSVValue($vv, $csv)
    {
        $r = new Row;
        foreach($vv as $cv) {
            $r->setCellValue($cv[0], $cv[1]);
        }
        $this->assertSame($csv, $r->toCSV());
    }

    public function toCSVAfterConstructProvider()
    {
        return [
            [[], "\r\n"],
            [['c0'], "c0\r\n"],
            [['c0', 'c1'], "c0,c1\r\n"],
            [['c0', 'c1', null, 'c3'], "c0,c1,,c3\r\n"],
            ];
    }

    /**
     * @dataProvider toCSVAfterConstructProvider
     */
    public function testToCSVAfterConstructValue($vv, $csv)
    {
        $r = new Row($vv);
        $this->assertSame($csv, $r->toCSV());
    }

    public function toCSVDelimiterProvider()
    {
        return [
            [[], "\t", "\r\n"],
            [['c0'], "\t", "c0\r\n"],
            [['c0', 'c1'], "**", "c0**c1\r\n"],
            [['c0', 'c1', null, 'c3'], "\t", "c0\tc1\t\tc3\r\n"],
            [['c0', 'c1', null, "c\t3", 'c4'], "\t", "c0\tc1\t\t\"c\t3\"\tc4\r\n"],
            ];
    }

    /**
     * @dataProvider toCSVDelimiterProvider
     */
    public function testToCSVDelimiterValue($vv, $d, $csv)
    {
        $r = new Row($vv);
        $this->assertSame($csv, $r->toCSV($d));
    }

    public function toCSVColumnsNumberProvider()
    {
        return [
            [[], 0, "\r\n"],
            [[], 1, "\r\n"],
            [[], 2, "|\r\n"],
            [[], 4, "|||\r\n"],
            [['a', 'b'], null, "a|b\r\n"],
            [['a', 'b'], 1, "a\r\n"],
            [['a', 'b'], 2, "a|b\r\n"],
            [['a', 'b'], 3, "a|b|\r\n"],
            [['a', 'b'], 5, "a|b|||\r\n"],
            ];
    }

    /**
     * @dataProvider toCSVColumnsNumberProvider
     */
    public function testToCSVColumnsNumberValue($vv, $n, $csv)
    {
        $r = new Row($vv);
        $this->assertSame($csv, $r->toCSV('|', $n));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testToCsvDelimiterEmpty()
    {
        $c = new Row;
        $c->toCSV('');
    }
}

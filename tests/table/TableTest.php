<?php

namespace fphammerle\helpers\tests\table;

use \fphammerle\helpers\table\Row;
use \fphammerle\helpers\table\Table;

class TableTest extends \PHPUnit_Framework_TestCase
{
    public function setCellValueProvider()
    {
        return [
            [0, 0, 1],
            [0, 0, 1.23],
            [0, 0, 'string'],
            [0, 0, true],
            [2, 3, 1],
            [2, 3, 1.23],
            [2, 3, 'string'],
            [2, 3, true],
            ];
    }

    /**
     * @dataProvider setCellValueProvider
     */
    public function testSetCellValue($r, $c, $v)
    {
        $t = new Table;
        $t->setCellValue($r, $c, $v);
        $this->assertSame($v, $t->getCell($r, $c)->value);
    }

    public function setCellValueMultipleProvider()
    {
        return [
            [[[0, 0, 'r0c0']]],
            [[[0, 0, 'r0c0'], [0, 1, 'r0c1']]],
            [[[1, 2, 'r1c2'], [2, 2, 'r2c2'], [0, 4, 'r0c4']]],
            ];
    }

    /**
     * @dataProvider setCellValueMultipleProvider
     */
    public function testSetCellMultipleValue($vv)
    {
        $t = new Table;
        foreach($vv as $cv) {
            $t->setCellValue($cv[0], $cv[1], $cv[2]);
        }
        foreach($vv as $cv) {
            $this->assertSame($cv[2], $t->getCell($cv[0], $cv[1])->value);
        }
    }

    public function setRowProvider()
    {
        return [
            [[]],
            [[0 => new Row]],
            [[2 => new Row, 4 => new Row(['aaa', 'bbb'])]],
            ];
    }

    /**
     * @dataProvider setRowProvider
     */
    public function testSetRow($rr)
    {
        $t = new Table;
        foreach($rr as $ri => $r) {
            $t->setRow($ri, $r);
        }
        foreach($rr as $ri => $r) {
            $this->assertSame($r, $t->getRow($ri));
        }
    }

    public function constructProvider()
    {
        return [
            [[]],
            [[[]]],
            [[['r0c0', 'r0c1']]],
            [[['r0c0', 'r0c1'], ['r1c0']]],
            [[['r0c0', 'r0c1'], ['r1c0'], 4 => ['r4c0', 3 => 'r4c3']]],
            ];
    }

    /**
     * @dataProvider constructProvider
     */
    public function testConstruct($rr)
    {
        $t = new Table($rr);
        foreach($rr as $row_index => $row_values) {
            foreach($row_values as $column_index => $cell_value) {
                $this->assertSame($cell_value, $t->getCell($row_index, $column_index)->value);
            }
        }
    }

    public function getColumnsCountProvider()
    {
        return [
            [[], 0],
            [[[]], 0],
            [[['r0c0', 'r0c1']], 2],
            [[['r0c0', 'r0c1'], ['r1c0']], 2],
            [[['r0c0', 'r0c1'], [3 => 'r1c0']], 4],
            [[2 => [0 => 'r2c0'], 1 => [2 => 'r1c2']], 3],
            ];
    }

    /**
     * @dataProvider getColumnsCountProvider
     */
    public function testGetColumnsCountValue($rr, $count)
    {
        $t = new Table($rr);
        $this->assertSame($count, $t->columnsCount);
    }

    public function toCSVProvider()
    {
        return [
            [[], ""],
            [[[]], "\r\n"],
            [[['r0c0', 'r0c1']], "r0c0,r0c1\r\n"],
            [[['r0c0', 'r"0c1"']], "r0c0,\"r\"\"0c1\"\"\"\r\n"],
            [[['r0c0', 'r0c1'], ['r1c0']], "r0c0,r0c1\r\nr1c0,\r\n"],
            [[2 => [0 => 'r2c0'], 1 => [2 => 'r1c2']], ",,\r\n,,r1c2\r\nr2c0,,\r\n"],
            [
                [['r0c0', 'r0c1'], ['r1c0'], 4 => ['r4c0', 3 => 'r4c3']],
                "r0c0,r0c1,,\r\nr1c0,,,\r\n,,,\r\n,,,\r\nr4c0,,,r4c3\r\n",
                ],
            [
                [
                    [1, 2, 3, 4],
                    ['a', 'b', null, 'd'],
                    ['A,B', 'CD', "EF\n", 'G'],
                    [3.14, '$#"%'],
                    ],
                "1,2,3,4\r\na,b,,d\r\n\"A,B\",CD,\"EF\r\",G\r\n3.14,\"$#\"\"%\",,\r\n",
                ],
            ];
    }

    /**
     * @dataProvider toCSVProvider
     */
    public function testToCSVValue($rr, $csv)
    {
        $t = new Table($rr);
        $this->assertSame($csv, $t->toCSV());
    }

    public function toCSVDelimiterProvider()
    {
        return [
            [[], '#', ""],
            [[[]], '#', "\r\n"],
            [[['r0c0', 'r0c1']], '#', "r0c0#r0c1\r\n"],
            [[['r0c0', 'r"0c1"']], '#', "r0c0#\"r\"\"0c1\"\"\"\r\n"],
            [[['r0c0', 'r0c1'], ['r1c0']], '#', "r0c0#r0c1\r\nr1c0#\r\n"],
            [[2 => [0 => 'r2c0'], 1 => [2 => 'r1c2']], '@', "@@\r\n@@r1c2\r\nr2c0@@\r\n"],
            ];
    }

    /**
     * @dataProvider toCSVDelimiterProvider
     */
    public function testToCSVDelimiter($rr, $d, $csv)
    {
        $t = new Table($rr);
        $this->assertSame($csv, $t->toCSV($d));
    }
}

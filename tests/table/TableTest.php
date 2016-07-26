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
                "1,2,3,4\r\na,b,,d\r\n\"A,B\",CD,\"EF\n\",G\r\n3.14,\"$#\"\"%\",,\r\n",
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

    public function getRowsCountProvider()
    {
        return [
            [[], 0],
            [[[]], 1],
            [[['r0c1']], 1],
            [[['r0c1'], []], 2],
            [[['r0c1'], [], 4 => []], 5],
            [[['r0c1'], [], 4 => ['r4c0']], 5],
            [[7 => [4 => 'r7c4']], 8],
            [[11 => []], 12],
            ];
    }

    /**
     * @dataProvider getRowsCountProvider
     */
    public function testGetRowsCount($vv, $rc)
    {
        $t = new Table($vv);
        $this->assertSame($rc, $t->rowsCount);
    }

    public function fromAssociativeArrayProvider()
    {
        return [
            [
                [],
                [
                    [],
                    ],
                ],
            [
                [
                    [],
                    ],
                [
                    [],
                    [],
                    ],
                ],
            [
                [
                    [],
                    [],
                    ],
                [
                    [],
                    [],
                    [],
                    ],
                ],
            [
                [
                    ['a' => 'A'],
                    ],
                [
                    ['a'],
                    ['A'],
                    ],
                ],
            [
                [
                    ['a' => null],
                    ],
                [
                    ['a'],
                    [null],
                    ],
                ],
            [
                [
                    ['a' => 'A', 'b' => 'B'],
                    ['c' => 'C'],
                    ],
                [
                    ['a', 'b', 'c'],
                    ['A', 'B', null],
                    [null, null, 'C'],
                    ],
                ],
            [
                [
                    ['a' => 'A', 'b' => 'B1'],
                    ['b' => 'B2', 'c' => 'C'],
                    ],
                [
                    ['a', 'b', 'c'],
                    ['A', 'B1', null],
                    [null, 'B2', 'C'],
                    ],
                ],
            [
                [
                    ['a' => 'A1', 'b' => 'B1', 'c' => 'C1'],
                    ['a' => 'A2', 'b' => 'B2', 'c' => 'C2'],
                    ['a' => 'A3', 'b' => 'B3', 'c' => 'C3'],
                    ],
                [
                    ['a',  'b',  'c'],
                    ['A1', 'B1', 'C1'],
                    ['A2', 'B2', 'C2'],
                    ['A3', 'B3', 'C3'],
                    ],
                ],
            [
                [
                    ['b' => 'B1'],
                    [],
                    ['a' => 'A3', 'b' => 'B3', 'c' => 'C3'],
                    ['d' => 'D4', 'e' => 'E4'],
                    ['a' => null, 'c' => 'C5'],
                    ],
                [
                    ['b',  'a',  'c',  'd',  'e' ],
                    ['B1', null, null, null, null],
                    [null, null, null, null, null],
                    ['B3', 'A3', 'C3', null, null],
                    [null, null, null, 'D4', 'E4'],
                    [null, null, 'C5', null, null],
                    ],
                ],
            [
                [
                    ['a' => null, 'd' => 1.23],
                    ['b' => ''],
                    ['a' => 3, 'b' => 'ok', 'd' => null],
                    ],
                [
                    ['a',  'd',  'b' ],
                    [null, 1.23, null],
                    [null, null, ''  ],
                    [3,    null, 'ok'],
                    ],
                ],
            ];
    }

    /**
     * @dataProvider fromAssociativeArrayProvider
     */
    public function testFromAssociativeArray($dict, $cells)
    {
        $t = Table::fromAssociativeArray($dict);
        $this->assertSame(sizeof($cells), $t->rowsCount);
        for($r = 0; $r < sizeof($cells); $r++) {
            $this->assertSame(
                sizeof($cells[$r]),
                $t->getRow($r)->columnsCount,
                sprintf(
                    '$cells[%d] = %s, $t->getRow(%d) = %s',
                    $r,
                    print_r($cells[$r], true),
                    $r,
                    print_r($t->getRow($r), true)
                    )
                );
            for($c = 0; $c < sizeof($cells[$r]); $c++) {
                if(isset($cells[$r][$c])) {
                    $this->assertSame($cells[$r][$c], $t->getCell($r, $c)->value);
                } else {
                    $this->assertNull($t->getCell($r, $c)->value);
                }
            }
        }
    }

    public function associativeArrayToCSVProvider()
    {
        return [
            [
                [
                    ['a' => 'A', 'b' => 'B1'],
                    ['b' => 'B2', 'c' => 'C'],
                    ],
                "a,b,c\r\nA,B1,\r\n,B2,C\r\n",
                ],
            [
                [
                    ['b' => 'B1'],
                    [],
                    ['a' => 'A3', 'b' => 'B3', 'c' => 'C3'],
                    ['d' => 'D4', 'e' => 'E4'],
                    ['a' => null, 'c' => 'C5'],
                    ],
                "b,a,c,d,e\r\nB1,,,,\r\n,,,,\r\nB3,A3,C3,,\r\n,,,D4,E4\r\n,,C5,,\r\n",
                ],
            ];
    }

    /**
     * @dataProvider associativeArrayToCSVProvider
     */
    public function testAssociativeArrayToCSV($rows_assoc, $csv)
    {
        $t = Table::fromAssociativeArray($rows_assoc);
        $this->assertSame($csv, $t->toCSV());
    }

    public function testAppendRow()
    {
        $t = new Table([['r0c0'], [1 => 'r1c1']]);
        $this->assertSame(2, $t->rowsCount);
        $this->assertSame(2, $t->columnsCount);
        $t->appendRow(new Row(['r2c0', 3 => 'r2c3']));
        $this->assertSame(3, $t->rowsCount);
        $this->assertSame(4, $t->columnsCount);
        $this->assertSame('r2c0', $t->getCell(2, 0)->value);
        $this->assertNull($t->getCell(2, 1)->value);
        $this->assertSame('r2c3', $t->getCell(2, 3)->value);
    }

    public function toTextProvider()
    {
        return [
            [
                new Table([]),
                '',
                ],
            [
                new Table([
                    [],
                    ]),
                "\n",
                ],
            [
                new Table([
                    [],
                    [],
                    ]),
                "\n\n",
                ],
            [
                new Table([
                    ['22'],
                    ]),
                "22\n",
                ],
            [
                new Table([
                    [null],
                    ]),
                "\n",
                ],
            [
                new Table([
                    ['1', '', '22', '333'],
                    ]),
                "1  22 333\n",
                ],
            [
                new Table([
                    [],
                    ['2', '', '22', '333'],
                    ['22'],
                    ]),
                implode("\n", [
                    '          ',
                    '2   22 333',
                    '22        ',
                    ]) . "\n",
                ],
            [
                new Table([
                    ['22', '1', '2',  '3'  ],
                    ['2',  '',  '22', '333'],
                    ]),
                implode("\n", [
                    '22 1 2  3  ',
                    '2    22 333',
                    ]) . "\n",
                ],
            [
                new Table([
                    ['33',  '',  '', '22', ''],
                    ['3',   '1', '', '2',  ''],
                    ['333', '',  '', '2',  ''],
                    ]),
                implode("\n", [
                    '33     22 ',
                    '3   1  2  ',
                    '333    2  ',
                    ]) . "\n",
                ],
            [
                new Table([
                    [true, false, null,  '',   1    ],
                    ['__', null,  '***', 3.33, '   '],
                    ]),
                implode("\n", [
                    '1  0          1  ',
                    '__   *** 3.33    ',
                    ]) . "\n",
                ],
            [
                new Table([
                    ['r0c0',     "r0\nc1", 'r0c2'  ],
                    ["r1\n\nc0", 'r1c1',   "\nr1c2"],
                    ["r2c0\n",   'r2c1',   'r2c2'  ],
                    ]),
                implode("\n", [
                    'r0c0 r0   r0c2',
                    '     c1       ',
                    'r1   r1c1     ',
                    '          r1c2',
                    'c0            ',
                    'r2c0 r2c1 r2c2',
                    '              ',
                    ]) . "\n",
                ],
            ];
    }

    /**
     * @dataProvider toTextProvider
     */
    public function testToText($table, $expected_text)
    {
        $this->assertSame($expected_text, $table->toText());
    }

    public function associativeArrayToTextProvider()
    {
        return [
            [
                [
                    ['A' => 'a', 'B' => 'bb'],
                    ['B' => 'bbb', 'CCC' => 'c'],
                    ['D' => 'd'],
                    ],
                implode("\n", [
                    'A B   CCC D',
                    'a bb       ',
                    '  bbb c    ',
                    '          d',
                    ]) . "\n",
                ],
            [
                [
                    ['a' => 'laaang', 'b' => 'kurz'],
                    ['a' => 'kurz', 'c' => "new\nline"],
                    ['a' => '1st', 'b' => "\n2nd", 'c' => "\n\n3rd"],
                    ['b' => "\ncenter\n"],
                    ],
                implode("\n", [
                    'a      b      c   ',
                    'laaang kurz       ',
                    'kurz          new ',
                    '              line',
                    '1st               ',
                    '       2nd        ',
                    '              3rd ',
                    '                  ',
                    '       center     ',
                    '                  ',
                    ]) . "\n",
                ],
            ];
    }

    /**
     * @dataProvider associativeArrayToTextProvider
     */
    public function testAssociativeArrayToText($rows, $expected_text)
    {
        $t = Table::fromAssociativeArray($rows);
        $this->assertSame($expected_text, $t->toText());
    }
}

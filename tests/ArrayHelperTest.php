<?php

namespace fphammerle\helpers\tests;

use fphammerle\helpers\ArrayHelper;

class ArrayHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testFlattenEmpty()
    {
        $this->assertEquals(
            [],
            ArrayHelper::flatten([])
            );
    }

    public function testFlattenFlat()
    {
        $this->assertEquals(
            [1, 2, 3],
            ArrayHelper::flatten([1, 2, 3])
            );
    }

    public function testFlatten2D()
    {
        $this->assertEquals(
            [1, 2, 3, 4, 5],
            ArrayHelper::flatten([1, [2, 3], [4], 5])
            );
    }

    public function testFlatten2DEmpty()
    {
        $this->assertEquals(
            [1, 2, 3, 4, 5],
            ArrayHelper::flatten([[], 1, [2, 3], [], [4], 5])
            );
    }

    public function testFlatten3D()
    {
        $this->assertEquals(
            [1, 2, 3, 4, 5, 6],
            ArrayHelper::flatten([1, [[2, 3], 4], [[5], [6]]])
            );
    }

    public function testFlatten3DEmpty()
    {
        $this->assertEquals(
            [1, 2, 3, 4, 5, 6],
            ArrayHelper::flatten([1, [[2, [], 3], 4, []], [[5], [6]], []])
            );
    }

    public function testFlattenAssociativeLetterFlat()
    {
        $this->assertEquals(
            [1, 2, 3, 4],
            ArrayHelper::flatten(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4])
            );
    }

    public function testFlattenAssociativeDigit()
    {
        $this->assertEquals(
            [1, 2, 3, 4],
            ArrayHelper::flatten([1 => 1, 2 => 2, 3 => 3, 4 => 4])
            );
    }

    public function testFlattenAssociativeDigitReverse()
    {
        $this->assertEquals(
            [1, 2, 3, 4],
            ArrayHelper::flatten([3 => 1, 2 => 2, 1 => 3, 0 => 4])
            );
    }

    public function testFlattenMixed2D()
    {
        $this->assertEquals(
            [1, 2, 3, 4, 5, 6, 7, 8],
            ArrayHelper::flatten([
                'a' => 1,
                'b' => [2, 3],
                'c' => ['#' => 4, '@' => 5],
                'd' => [6, '$' => 7],
                8,
                ])
            );
    }

    public function mapProvider()
    {
        return [
            [
                null,
                function($v) { return 1; },
                1,
                ],
            [
                'string',
                function($v) { return strrev($v); },
                'gnirts',
                ],
            [
                'string',
                function($v) { return null; },
                null,
                ],
            [
                'string',
                function($k, $v) { return [$v => $k]; },
                ['string' => null],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($v) { return $v; },
                ['a' => 1, 'b' => 2, 'c' => 3],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($v) { return $v < 2 ? null : $v; },
                ['a' => null, 'b' => 2, 'c' => 3],
                ],
            [
                ['a' => 1, 'b' => null, 'c' => 3],
                function($v) { return $v; },
                ['a' => 1, 'b' => null, 'c' => 3],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($v) { return $v * $v; },
                ['a' => 1, 'b' => 4, 'c' => 9],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return $k . $v; },
                ['a' => 'a1', 'b' => 'b2', 'c' => 'c3'],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return [strtoupper($k) => $v]; },
                ['a' => ['A' => 1], 'b' => ['B' => 2], 'c' => ['C' => 3]],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => null],
                function($k, $v) { return $k . (is_null($v) ? ' null' : ' not null'); },
                ['a' => 'a not null', 'b' => 'b not null', 'c' => 'c null'],
                ],
            ];
    }

    /**
     * @dataProvider mapProvider
     */
    public function testMap($source, $callback, $expected)
    {
        $this->assertSame($expected, ArrayHelper::map($source, $callback));
    }

    public function mapIfSetProvider()
    {
        return [
            [
                null,
                function($v) { return 1; },
                null,
                ],
            [
                'string',
                function($v) { return strrev($v); },
                'gnirts',
                ],
            [
                'string',
                function($v) { return null; },
                null,
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($v) { return $v; },
                ['a' => 1, 'b' => 2, 'c' => 3],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($v) { return $v < 2 ? null : $v; },
                ['a' => null, 'b' => 2, 'c' => 3],
                ],
            [
                ['a' => 1, 'b' => null, 'c' => 3],
                function($v) { return (string)$v; },
                ['a' => '1', 'b' => null, 'c' => '3'],
                ],
            [
                ['a' => 1, 'b' => 2, 'b' => 3],
                function($v) { return $v * $v; },
                ['a' => 1, 'b' => 4, 'b' => 9],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return $k . $v; },
                ['a' => 'a1', 'b' => 'b2', 'c' => 'c3'],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return [strtoupper($k) => $v]; },
                ['a' => ['A' => 1], 'b' => ['B' => 2], 'c' => ['C' => 3]],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => null],
                function($k, $v) { return $k . (is_null($v) ? ' null' : ' not null'); },
                ['a' => 'a not null', 'b' => 'b not null', 'c' => null],
                ],
            ];
    }

    /**
     * @dataProvider mapIfSetProvider
     */
    public function testMapIfSet($source, $callback, $expected)
    {
        $this->assertSame($expected, ArrayHelper::mapIfSet($source, $callback));
    }

    public function multimapProvider()
    {
        return [
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return [$k => $v]; },
                ['a' => 1, 'b' => 2, 'c' => 3],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return [$v => $k]; },
                [1 => 'a', 2 => 'b', 3 => 'c'],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return [strtoupper($k) => $v * $v]; },
                ['A' => 1, 'B' => 4, 'C' => 9],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return [$k => null]; },
                ['a' => null, 'b' => null, 'c' => null],
                ],
            [
                ['a' => 1, 'b' => 2, 'b' => 3],
                function($k, $v) { return [$k => $v]; },
                ['a' => 1, 'b' => 3],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return ($v % 2) ? [$k => $v * $v] : null; },
                ['a' => 1, 'c' => 9],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return ($v % 2) ? [$k => $v * $v] : []; },
                ['a' => 1, 'c' => 9],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return [$k => $v, $k.'^2' => $v * $v]; },
                ['a' => 1, 'a^2' => 1, 'b' => 2, 'b^2' => 4, 'c' => 3, 'c^2' => 9],
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return [$k => [[$v]], strtoupper($k) => []]; },
                ['a' => [[1]], 'A' => [], 'b' => [[2]], 'B' => [], 'c' => [[3]], 'C' => []],
                ],
            ];
    }

    /**
     * @dataProvider multimapProvider
     */
    public function testMultimap($source, $callback, $expected)
    {
        $this->assertSame($expected, ArrayHelper::multimap($source, $callback));
    }

    public function multimapUnexpectedValueProvider()
    {
        return [
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return $v; },
                ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                function($k, $v) { return 'string'; },
                ],
            ];
    }

    /**
     * @dataProvider multimapUnexpectedValueProvider
     * @expectedException \UnexpectedValueException
     */
    public function testMultimapUnexpectedValue($source, $callback)
    {
        ArrayHelper::multimap($source, $callback);
    }
}

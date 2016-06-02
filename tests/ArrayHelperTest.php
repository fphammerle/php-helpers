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
}

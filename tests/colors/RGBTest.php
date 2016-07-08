<?php

namespace fphammerle\helpers\tests\colors;

use \fphammerle\helpers\colors\RGB;

class RGBTest extends \PHPUnit_Framework_TestCase
{
    const comparison_precision = 0.00001;

    public function testConstruct0()
    {
        $c = new RGB();
        $this->assertEquals(0.0, $c->red,   '', self::comparison_precision);
        $this->assertEquals(0.0, $c->green, '', self::comparison_precision);
        $this->assertEquals(0.0, $c->blue,  '', self::comparison_precision);
    }

    public function testConstruct1()
    {
        $c = new RGB(1.0);
        $this->assertEquals(1.0, $c->red,   '', self::comparison_precision);
        $this->assertEquals(0.0, $c->green, '', self::comparison_precision);
        $this->assertEquals(0.0, $c->blue,  '', self::comparison_precision);
    }

    public function testConstruct2()
    {
        $c = new RGB(1.0, 0.5);
        $this->assertEquals(1.0, $c->red,   '', self::comparison_precision);
        $this->assertEquals(0.5, $c->green, '', self::comparison_precision);
        $this->assertEquals(0.0, $c->blue,  '', self::comparison_precision);
    }

    public function testConstruct3()
    {
        $c = new RGB(0.3, 0.2, 1);
        $this->assertEquals(0.3, $c->red,   '', self::comparison_precision);
        $this->assertEquals(0.2, $c->green, '', self::comparison_precision);
        $this->assertEquals(1.0, $c->blue,  '', self::comparison_precision);
    }

    public function equalsProvider()
    {
        return [
            [new RGB(0.0, 0.0, 0.0  ), new RGB(0.0,              0.0,              0.0             )],
            [new RGB(0.0, 0.0, 0.0  ), new RGB(0,                0,                0               )],
            [new RGB(0.0, 0.0, 0.0  ), new RGB(pow(10, -10),     pow(10, -10),     pow(10, -10)    )],
            [new RGB(1.0, 1.0, 1.0  ), new RGB(1.0,              1.0,              1.0             )],
            [new RGB(1.0, 1.0, 1.0  ), new RGB(1,                1,                1               )],
            [new RGB(1.0, 1.0, 1.0  ), new RGB(1 - pow(10, -10), 1 - pow(10, -10), 1 - pow(10, -10))],
            [new RGB(0.1, 0.2, 0.3  ), new RGB(0.1,              0.2,              0.3             )],
            [new RGB(1/7, 1/9, 1/321), new RGB(1/14*2,           1/36*4,           1/321           )],
            ];
    }

    /**
     * @dataProvider equalsProvider
     */
    public function testEquals($a, $b)
    {
        $this->assertTrue($a->equals($b));
        $this->assertTrue($b->equals($a));
    }

    public function unequalsProvider()
    {
        return [
            [new RGB(0.0, 0.0, 0.0), new RGB(0.0,             0.0,             pow(10, -4)    )],
            [new RGB(0.0, 0.0, 0.0), new RGB(0.0,             pow(10, -4),     0.0            )],
            [new RGB(0.0, 0.0, 0.0), new RGB(0.0,             pow(10, -4),     pow(10, -4)    )],
            [new RGB(0.0, 0.0, 0.0), new RGB(pow(10, -4),     0.0,             0.0            )],
            [new RGB(0.0, 0.0, 0.0), new RGB(pow(10, -4),     pow(10, -4),     0.0            )],
            [new RGB(0.0, 0.0, 0.0), new RGB(pow(10, -4),     pow(10, -4),     pow(10, -4)    )],
            [new RGB(0.1, 0.2, 0.3), new RGB(0.1,             0.2,             0.4            )],
            [new RGB(0.1, 0.2, 0.3), new RGB(0.1,             0.4,             0.3            )],
            [new RGB(0.1, 0.2, 0.3), new RGB(0.4,             0.2,             0.3            )],
            [new RGB(1.0, 1.0, 1.0), new RGB(1 - pow(10, -4), 1 - pow(10, -4), 1 - pow(10, -4))],
            [new RGB(1.0, 1.0, 1.0), new RGB(1 - pow(10, -4), 1 - pow(10, -4), 1.0            )],
            [new RGB(1.0, 1.0, 1.0), new RGB(1 - pow(10, -4), 1.0,             1.0            )],
            [new RGB(1.0, 1.0, 1.0), new RGB(1.0,             1 - pow(10, -4), 1 - pow(10, -4))],
            [new RGB(1.0, 1.0, 1.0), new RGB(1.0,             1 - pow(10, -4), 1.0            )],
            [new RGB(1.0, 1.0, 1.0), new RGB(1.0,             1.0,             1 - pow(10, -4))],
            ];
    }

    /**
     * @dataProvider unequalsProvider
     */
    public function testUnequals($a, $b)
    {
        $this->assertFalse($a->equals($b));
        $this->assertFalse($b->equals($a));
    }

    public function getRedProvider()
    {
        return [
            [new RGB(0,   0.1, 0.2), 0.0],
            [new RGB(0,   0.2, 0.3), 0.0],
            [new RGB(0.1, 0.2, 0.3), 0.1],
            [new RGB(1/7, 0.2, 0.3), 1/7],
            ];
    }

    /**
     * @dataProvider getRedProvider
     */
    public function testGetRed($c, $h)
    {
        $this->assertEquals($h, $c->red,      '', self::comparison_precision);
        $this->assertEquals($h, $c->getRed(), '', self::comparison_precision);
    }

    public function setRedProvider()
    {
        return [
            [0,   0.0],
            [0,   0.0],
            [0.1, 0.1],
            [1/7, 1/7],
            ];
    }

    /**
     * @dataProvider setRedProvider
     */
    public function testSetRed($s, $g)
    {
        $c = new RGB(0, 0, 0);
        $c->setRed($s);
        $this->assertEquals($g, $c->red, '', self::comparison_precision);

        $c = new RGB(0, 0, 0);
        $c->red = $s;
        $this->assertEquals($g, $c->red, '', self::comparison_precision);
    }

    public function getGreenProvider()
    {
        return [
            [new RGB(0.1, 0,   0.2), 0.0],
            [new RGB(0.2, 0,   0.3), 0.0],
            [new RGB(0.2, 0.1, 0.3), 0.1],
            [new RGB(0.2, 1/7, 0.3), 1/7],
            ];
    }

    /**
     * @dataProvider getGreenProvider
     */
    public function testGetGreen($c, $h)
    {
        $this->assertEquals($h, $c->green,      '', self::comparison_precision);
        $this->assertEquals($h, $c->getGreen(), '', self::comparison_precision);
    }

    public function setGreenProvider()
    {
        return [
            [0,   0.0],
            [0,   0.0],
            [0.1, 0.1],
            [1/7, 1/7],
            ];
    }

    /**
     * @dataProvider setGreenProvider
     */
    public function testSetGreen($s, $g)
    {
        $c = new RGB(0, 0, 0);
        $c->setGreen($s);
        $this->assertEquals($g, $c->green, '', self::comparison_precision);

        $c = new RGB(0, 0, 0);
        $c->green = $s;
        $this->assertEquals($g, $c->green, '', self::comparison_precision);
    }

    public function getBlueProvider()
    {
        return [
            [new RGB(0.1, 0.2, 0  ), 0.0],
            [new RGB(0.2, 0.3, 0  ), 0.0],
            [new RGB(0.2, 0.3, 0.1), 0.1],
            [new RGB(0.2, 0.3, 1/7), 1/7],
            ];
    }

    /**
     * @dataProvider getBlueProvider
     */
    public function testGetBlue($c, $h)
    {
        $this->assertEquals($h, $c->blue,      '', self::comparison_precision);
        $this->assertEquals($h, $c->getBlue(), '', self::comparison_precision);
    }

    public function setBlueProvider()
    {
        return [
            [0,   0.0],
            [0,   0.0],
            [0.1, 0.1],
            [1/7, 1/7],
            ];
    }

    /**
     * @dataProvider setBlueProvider
     */
    public function testSetBlue($s, $g)
    {
        $c = new RGB(0, 0, 0);
        $c->setBlue($s);
        $this->assertEquals($g, $c->blue, '', self::comparison_precision);

        $c = new RGB(0, 0, 0);
        $c->blue = $s;
        $this->assertEquals($g, $c->blue, '', self::comparison_precision);
    }

    public function getTupleProvider()
    {
        return [
            [new RGB(0.0, 0.0, 0.0), [0.0, 0.0, 0.0]],
            [new RGB(0.2, 0.3, 0.4), [0.2, 0.3, 0.4]],
            [new RGB(0.8, 0.9, 1.0), [0.8, 0.9, 1.0]],
            [new RGB(1/7, 1/9, 1/3), [1/7, 1/9, 1/3]],
            ];
    }

    /**
     * @dataProvider getTupleProvider
     */
    public function testGetTuple($c, $h)
    {
        $this->assertEquals($h, $c->tuple,      '', self::comparison_precision);
        $this->assertEquals($h, $c->getTuple(), '', self::comparison_precision);
    }

    public function getDigitalTupleProvider()
    {
        return [
            [new RGB(0.0, 0.0, 0.0), 1, [0, 0, 0]],
            [new RGB(0.0, 0.0, 0.0), 2, [0, 0, 0]],
            [new RGB(0.0, 0.0, 0.0), 3, [0, 0, 0]],
            [new RGB(0.2, 0.3, 0.4), 1, [0, 0, 0]],
            [new RGB(0.2, 0.3, 0.4), 2, [1, 1, 1]],
            [new RGB(0.2, 0.3, 0.4), 3, [1, 2, 3]],
            [new RGB(0.8, 0.9, 1.0), 1, [1, 1, 1]],
            [new RGB(0.8, 0.9, 1.0), 2, [2, 3, 3]],
            [new RGB(0.8, 0.9, 1.0), 3, [6, 6, 7]],
            [new RGB(1/7, 1/9, 1/3), 1, [0, 0, 0]],
            [new RGB(1/7, 1/9, 1/3), 2, [0, 0, 1]],
            [new RGB(1/7, 1/9, 1/3), 3, [1, 1, 2]],
            [new RGB(1/8, 1/4, 1/2), 3, [1, 2, 4]],
            ];
    }

    /**
     * @dataProvider getDigitalTupleProvider
     */
    public function testGetDigitalTuple($c, $bits, $tuple)
    {
        $this->assertSame($tuple, $c->getDigitalTuple($bits));
    }
}

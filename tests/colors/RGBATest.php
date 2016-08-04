<?php

namespace fphammerle\helpers\tests\colors;

use \fphammerle\helpers\colors\RGBA;

class RGBATest extends \PHPUnit_Framework_TestCase
{
    const comparison_precision = 0.00001;

    public function testConstruct0()
    {
        $c = new RGBA();
        $this->assertEquals(0.0, $c->red,   '', self::comparison_precision);
        $this->assertEquals(0.0, $c->green, '', self::comparison_precision);
        $this->assertEquals(0.0, $c->blue,  '', self::comparison_precision);
        $this->assertEquals(1.0, $c->alpha, '', self::comparison_precision);
    }

    public function testConstruct1()
    {
        $c = new RGBA(1.0);
        $this->assertEquals(1.0, $c->red,   '', self::comparison_precision);
        $this->assertEquals(0.0, $c->green, '', self::comparison_precision);
        $this->assertEquals(0.0, $c->blue,  '', self::comparison_precision);
        $this->assertEquals(1.0, $c->alpha, '', self::comparison_precision);
    }

    public function testConstruct2()
    {
        $c = new RGBA(1.0, 0.5);
        $this->assertEquals(1.0, $c->red,   '', self::comparison_precision);
        $this->assertEquals(0.5, $c->green, '', self::comparison_precision);
        $this->assertEquals(0.0, $c->blue,  '', self::comparison_precision);
        $this->assertEquals(1.0, $c->alpha, '', self::comparison_precision);
    }

    public function testConstruct3()
    {
        $c = new RGBA(0.3, 0.2, 1, 0.6);
        $this->assertEquals(0.3, $c->red,   '', self::comparison_precision);
        $this->assertEquals(0.2, $c->green, '', self::comparison_precision);
        $this->assertEquals(1.0, $c->blue,  '', self::comparison_precision);
        $this->assertEquals(0.6, $c->alpha, '', self::comparison_precision);
    }

    public function equalsProvider()
    {
        return [
            [new RGBA(0.0, 0.0, 0.0, 0.0), new RGBA(0.0,              0.0,              0.0,              0.0             )],
            [new RGBA(0.0, 0.0, 0.0, 0.0), new RGBA(0,                0,                0,                0               )],
            [new RGBA(0.0, 0.0, 0.0, 0.0), new RGBA(pow(10, -10),     pow(10, -10),     pow(10, -10),     pow(10, -10)    )],
            [new RGBA(1.0, 1.0, 1.0, 1.0), new RGBA(1.0,              1.0,              1.0,              1.0             )],
            [new RGBA(1.0, 1.0, 1.0, 1.0), new RGBA(1,                1,                1,                1               )],
            [new RGBA(1.0, 1.0, 1.0, 1.0), new RGBA(1 - pow(10, -10), 1 - pow(10, -10), 1 - pow(10, -10), 1 - pow(10, -10))],
            [new RGBA(0.1, 0.2, 0.3, 0.4), new RGBA(0.1,              0.2,              0.3,              0.4             )],
            [new RGBA(1/7, 1/9, 1/6, 1/3), new RGBA(1/14*2,           1/36*4,           6/36,             9/27            )],
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
            [new RGBA(0.0, 0.0, 0.0), new RGBA(0.0,             0.0,             pow(10, -4)    )],
            [new RGBA(0.0, 0.0, 0.0), new RGBA(0.0,             pow(10, -4),     0.0            )],
            [new RGBA(0.0, 0.0, 0.0), new RGBA(0.0,             pow(10, -4),     pow(10, -4)    )],
            [new RGBA(0.0, 0.0, 0.0), new RGBA(pow(10, -4),     0.0,             0.0            )],
            [new RGBA(0.0, 0.0, 0.0), new RGBA(pow(10, -4),     pow(10, -4),     0.0            )],
            [new RGBA(0.0, 0.0, 0.0), new RGBA(pow(10, -4),     pow(10, -4),     pow(10, -4)    )],
            [new RGBA(0.1, 0.2, 0.3), new RGBA(0.1,             0.2,             0.4            )],
            [new RGBA(0.1, 0.2, 0.3), new RGBA(0.1,             0.4,             0.3            )],
            [new RGBA(0.1, 0.2, 0.3), new RGBA(0.4,             0.2,             0.3            )],
            [new RGBA(1.0, 1.0, 1.0), new RGBA(1 - pow(10, -4), 1 - pow(10, -4), 1 - pow(10, -4))],
            [new RGBA(1.0, 1.0, 1.0), new RGBA(1 - pow(10, -4), 1 - pow(10, -4), 1.0            )],
            [new RGBA(1.0, 1.0, 1.0), new RGBA(1 - pow(10, -4), 1.0,             1.0            )],
            [new RGBA(1.0, 1.0, 1.0), new RGBA(1.0,             1 - pow(10, -4), 1 - pow(10, -4))],
            [new RGBA(1.0, 1.0, 1.0), new RGBA(1.0,             1 - pow(10, -4), 1.0            )],
            [new RGBA(1.0, 1.0, 1.0), new RGBA(1.0,             1.0,             1 - pow(10, -4))],
            [new RGBA(0.0, 0.0, 0.0, 0.0), new RGBA(0.0, 0.0, 0.0, pow(10, -4))],
            [new RGBA(0.1, 0.0, 0.0, 0.0), new RGBA(0.0, 0.0, 0.0, 0.0        )],
            [new RGBA(0.0, 0.2, 0.0, 0.0), new RGBA(0.0, 0.0, 0.0, 0.0        )],
            [new RGBA(0.0, 0.0, 0.3, 0.0), new RGBA(0.0, 0.0, 0.0, 0.0        )],
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
            [new RGBA(0,   0.1, 0.2, 0.4), 0.0],
            [new RGBA(0.0, 0.2, 0.3, 0.4), 0.0],
            [new RGBA(0.1, 0.2, 0.3, 0.4), 0.1],
            [new RGBA(1/7, 0.2, 0.3, 0.4), 1/7],
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
        $c = new RGBA(0, 0, 0, 0);
        $c->setRed($s);
        $this->assertEquals($g, $c->red, '', self::comparison_precision);

        $c = new RGBA(0, 0, 0, 0);
        $c->red = $s;
        $this->assertEquals($g, $c->red, '', self::comparison_precision);
    }

    public function getGreenProvider()
    {
        return [
            [new RGBA(0.1, 0,   0.2, 0.4), 0.0],
            [new RGBA(0.2, 0.0, 0.3, 0.4), 0.0],
            [new RGBA(0.2, 0.1, 0.3, 0.4), 0.1],
            [new RGBA(0.2, 1/7, 0.3, 0.4), 1/7],
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
        $c = new RGBA(0, 0, 0, 0);
        $c->setGreen($s);
        $this->assertEquals($g, $c->green, '', self::comparison_precision);

        $c = new RGBA(0, 0, 0, 0);
        $c->green = $s;
        $this->assertEquals($g, $c->green, '', self::comparison_precision);
    }

    public function getBlueProvider()
    {
        return [
            [new RGBA(0.1, 0.2, 0,   0.4), 0.0],
            [new RGBA(0.2, 0.3, 0.0, 0.4), 0.0],
            [new RGBA(0.2, 0.3, 0.1, 0.4), 0.1],
            [new RGBA(0.2, 0.3, 1/7, 0.4), 1/7],
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
        $c = new RGBA(0, 0, 0, 0);
        $c->setBlue($s);
        $this->assertEquals($g, $c->blue, '', self::comparison_precision);

        $c = new RGBA(0, 0, 0, 0);
        $c->blue = $s;
        $this->assertEquals($g, $c->blue, '', self::comparison_precision);
    }

    public function getAlphaProvider()
    {
        return [
            [new RGBA(0.1, 0.2, 0.4, 0  ), 0.0],
            [new RGBA(0.2, 0.3, 0.4, 0.0), 0.0],
            [new RGBA(0.2, 0.3, 0.4, 0.1), 0.1],
            [new RGBA(0.2, 0.3, 0.4, 1/7), 1/7],
            ];
    }

    /**
     * @dataProvider getAlphaProvider
     */
    public function testGetAlpha($c, $h)
    {
        $this->assertEquals($h, $c->alpha,      '', self::comparison_precision);
        $this->assertEquals($h, $c->getAlpha(), '', self::comparison_precision);
    }

    public function setAlphaProvider()
    {
        return [
            [0,   0.0],
            [0,   0.0],
            [0.1, 0.1],
            [1/7, 1/7],
            ];
    }

    /**
     * @dataProvider setAlphaProvider
     */
    public function testSetAlpha($s, $g)
    {
        $c = new RGBA(0, 0, 0, 0);
        $c->setAlpha($s);
        $this->assertEquals($g, $c->alpha, '', self::comparison_precision);

        $c = new RGBA(0, 0, 0, 0);
        $c->alpha = $s;
        $this->assertEquals($g, $c->alpha, '', self::comparison_precision);
    }

    public function getTupleProvider()
    {
        return [
            [new RGBA(0.0, 0.0, 0.0, 0.0), [0.0, 0.0, 0.0, 0.0]],
            [new RGBA(0.2, 0.3, 0.4, 0.5), [0.2, 0.3, 0.4, 0.5]],
            [new RGBA(0.8, 0.9, 1.0, 0.7), [0.8, 0.9, 1.0, 0.7]],
            [new RGBA(1/7, 1/9, 1/3, 1/4), [1/7, 1/9, 1/3, 1/4]],
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
            [new RGBA(0.0, 0.0, 0.0, 0.0), 1, [0, 0, 0, 0]],
            [new RGBA(0.0, 0.0, 0.0, 0.0), 2, [0, 0, 0, 0]],
            [new RGBA(0.0, 0.0, 0.0, 0.0), 3, [0, 0, 0, 0]],
            [new RGBA(0.2, 0.3, 0.4, 0.5), 1, [0, 0, 0, 1]],
            [new RGBA(0.2, 0.3, 0.4, 0.5), 2, [1, 1, 1, 2]],
            [new RGBA(0.2, 0.3, 0.4, 0.5), 3, [1, 2, 3, 4]],
            [new RGBA(0.8, 0.9, 1.0, 0.7), 1, [1, 1, 1, 1]],
            [new RGBA(0.8, 0.9, 1.0, 0.7), 2, [2, 3, 3, 2]],
            [new RGBA(0.8, 0.9, 1.0, 0.7), 3, [6, 6, 7, 5]],
            [new RGBA(1/7, 1/9, 1/3, 1/8), 1, [0, 0, 0, 0]],
            [new RGBA(1/7, 1/9, 1/3, 1/8), 2, [0, 0, 1, 0]],
            [new RGBA(1/7, 1/9, 1/3, 1/8), 3, [1, 1, 2, 1]],
            [new RGBA(1/8, 1/4, 1/2, 1/4), 3, [1, 2, 4, 2]],
            ];
    }

    /**
     * @dataProvider getDigitalTupleProvider
     */
    public function testGetDigitalTuple($c, $bits, $tuple)
    {
        $this->assertSame($tuple, $c->getDigitalTuple($bits));
    }

    public function getDigitalHexTupleProvider()
    {
        return [
            [new RGBA(0.0, 0.0, 0.0, 0.0), 1, ['0',  '0',  '0',  '0' ]],
            [new RGBA(0.0, 0.0, 0.0, 0.0), 2, ['0',  '0',  '0',  '0' ]],
            [new RGBA(0.0, 0.0, 0.0, 0.0), 3, ['0',  '0',  '0',  '0' ]],
            [new RGBA(0.2, 0.3, 0.4, 0.5), 1, ['0',  '0',  '0',  '1' ]],
            [new RGBA(0.2, 0.3, 0.4, 0.5), 2, ['1',  '1',  '1',  '2' ]],
            [new RGBA(0.2, 0.3, 0.4, 0.5), 3, ['1',  '2',  '3',  '4' ]],
            [new RGBA(0.8, 0.9, 1.0, 0.7), 1, ['1',  '1',  '1',  '1' ]],
            [new RGBA(0.8, 0.9, 1.0, 0.7), 2, ['2',  '3',  '3',  '2' ]],
            [new RGBA(0.8, 0.9, 1.0, 0.7), 4, ['c',  'e',  'f',  'b' ]],
            [new RGBA(1/7, 1/9, 1/3, 1/8), 1, ['0',  '0',  '0',  '0' ]],
            [new RGBA(1/7, 1/9, 1/3, 1/8), 5, ['4',  '3',  'a',  '4' ]],
            [new RGBA(1/7, 1/9, 1/3, 1/8), 5, ['4',  '3',  'a',  '4' ]],
            [new RGBA(1/8, 1/4, 1/2, 1/4), 6, ['8',  '10', '20', '10']],
            [new RGBA(1/4, 1/2, 1/1, 1/8), 8, ['40', '80', 'ff', '20']],
            ];
    }

    /**
     * @dataProvider getDigitalHexTupleProvider
     */
    public function testGetDigitalHexTuple($c, $bits, $tuple)
    {
        $this->assertSame($tuple, $c->getDigitalHexTuple($bits));
    }

    public function getHexTripletProvider()
    {
        return [
            [new RGBA(1/4, 1/2, 1/1), '4080ff'],
            [new RGBA(0.3, 0.6, 0.9), '4d99e6'],
            [new RGBA(1.0, 1/3, 0.0), 'ff5500'],
            [new RGBA(1/7, 1/8, 1/9), '24201c'],
            [new RGBA(1/16, 1/32, 1/64), '100804'],
            [new RGBA(1/32, 1/64, 1/96), '080403'],
            ];
    }

    /**
     * @dataProvider getHexTripletProvider
     */
    public function testGetHexTriplet($c, $triplet)
    {
        $this->assertSame($triplet, $c->hexTriplet);
    }
}

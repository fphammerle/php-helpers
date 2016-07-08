<?php

namespace fphammerle\helpers\tests\colors;

use \fphammerle\helpers\colors\HSV;

class HSVTest extends \PHPUnit_Framework_TestCase
{
    const comparison_precision = 0.00001;

    public function testConstruct0()
    {
        $c = new HSV();
        $this->assertEquals(0.0, $c->hue,        '', self::comparison_precision);
        $this->assertEquals(0.0, $c->saturation, '', self::comparison_precision);
        $this->assertEquals(0.0, $c->value,      '', self::comparison_precision);
    }

    public function testConstruct1()
    {
        $c = new HSV(pi());
        $this->assertEquals(pi(), $c->hue,        '', self::comparison_precision);
        $this->assertEquals(0.0,  $c->saturation, '', self::comparison_precision);
        $this->assertEquals(0.0,  $c->value,      '', self::comparison_precision);
    }

    public function testConstruct2()
    {
        $c = new HSV(pi(), 0.2);
        $this->assertEquals(pi(), $c->hue,        '', self::comparison_precision);
        $this->assertEquals(0.2,  $c->saturation, '', self::comparison_precision);
        $this->assertEquals(0.0,  $c->value,      '', self::comparison_precision);
    }

    public function testConstruct3()
    {
        $c = new HSV(pi(), 0.2, 1);
        $this->assertEquals(pi(), $c->hue,        '', self::comparison_precision);
        $this->assertEquals(0.2,  $c->saturation, '', self::comparison_precision);
        $this->assertEquals(1.0,  $c->value,      '', self::comparison_precision);
    }

    public function equalsProvider()
    {
        return [
            [new HSV(0,    0,   0.0 ), new HSV(0.0,          0.0, 0           )],
            [new HSV(0.0,  0.0, 0.0 ), new HSV(0.0,          0.0, 0.0         )],
            [new HSV(0.0,  0.4, 0.99), new HSV(0.0,          0.4, 0.99        )],
            [new HSV(2.21, 0.4, 0.99), new HSV(2.21,         0.4, 0.99        )],
            [new HSV(2.21, 0.9, 0.0 ), new HSV(2.21,         0.9, pow(10, -20))],
            [new HSV(3,    0.3, 1   ), new HSV(1/2*6,        0.3, 1.0         )],
            [new HSV(pi(), 0.0, 0.0 ), new HSV(deg2rad(180), 0.0, 0.0         )],
            [new HSV(pi(), 0.7, 0.0 ), new HSV(deg2rad(180), 0.7, 0.0         )],
            [new HSV(sqrt(pi()), sqrt(0.7), sqrt(1/33) ), new HSV(sqrt(deg2rad(180)), sqrt(7)/sqrt(10), 1/sqrt(33))],
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
            [new HSV(0.0,  0.0,  0.0), new HSV(0.0,          0.0,          pow(10, -4) )],
            [new HSV(0.0,  0.0,  0.0), new HSV(0.0,          pow(10, -4),  0.0         )],
            [new HSV(0.0,  0.0,  0.0), new HSV(pow(10, -4),  0.0,          0.0         )],
            [new HSV(0.5,  0.5,  0.5), new HSV(0.5+1.0/9999, 0.5,          0.5         )],
            [new HSV(0.5,  0.5,  0.5), new HSV(0.5,          0.5+1.0/9999, 0.5         )],
            [new HSV(0.5,  0.5,  0.5), new HSV(0.5,          0.5,          0.5+1.0/9999)],
            [new HSV(pi(), 1/33, 0.1), new HSV(1.53,         pow(10, -4),  0.32        )],
            [new HSV(pi(), 1/33, 0.1), new HSV(2.32,         0.32,         pow(10, -4) )],
            [new HSV(pi(), 1/33, 0.1), new HSV(2.32,         1/33,         pow(10, -4) )],
            [new HSV(pi(), 1/33, 0.1), new HSV(pi(),         1/33,         pow(10, -4) )],
            [new HSV(pi(), 1/33, 0.1), new HSV(pi(),         pow(10, -4),  0.1         )],
            [new HSV(pi(), 1/33, 0.1), new HSV(pi(),         pow(10, -4),  0.32        )],
            [new HSV(pi(), 1/33, 0.1), new HSV(pow(10, -4),  0.92,         0.1         )],
            [new HSV(pi(), 1/33, 0.1), new HSV(pow(10, -4),  0.92,         0.4         )],
            [new HSV(pi(), 1/33, 0.1), new HSV(pow(10, -4),  1/33,         0.1         )],
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

    public function getHueProvider()
    {
        return [
            [new HSV(0,    0,   0.0          ), 0.0,              ],
            [new HSV(0.0,  0.0, 0.0          ), 0.0,              ],
            [new HSV(2.21, 0.4, 0.99         ), 2.21,             ],
            [new HSV(3,    0.3, 1            ), 1/2*6,            ],
            [new HSV(pi(), 0.7, 0.0          ), deg2rad(180)      ],
            [new HSV(sqrt(pi()), sqrt(0.7), 1), sqrt(deg2rad(180))],
            ];
    }

    /**
     * @dataProvider getHueProvider
     */
    public function testGetHue($c, $h)
    {
        $this->assertEquals($h, $c->hue,      '', self::comparison_precision);
        $this->assertEquals($h, $c->getHue(), '', self::comparison_precision);
    }

    public function setHueProvider()
    {
        return [
            [0,          0.0,              ],
            [0.0,        0.0,              ],
            [2.21,       2.21,             ],
            [3,          1/2*6,            ],
            [pi(),       deg2rad(180)      ],
            [sqrt(pi()), sqrt(deg2rad(180))],
            ];
    }

    /**
     * @dataProvider setHueProvider
     */
    public function testSetHue($s, $g)
    {
        $c = new HSV(0, 0, 0);
        $c->setHue($s);
        $this->assertEquals($g, $c->hue, '', self::comparison_precision);

        $c = new HSV(0, 0, 0);
        $c->hue = $s;
        $this->assertEquals($g, $c->hue, '', self::comparison_precision);
    }

    public function getSaturationProvider()
    {
        return [
            [new HSV(0,    0,           0.0         ), 0.0,     ],
            [new HSV(0.0,  0.0,         0.0         ), 0.0,     ],
            [new HSV(2.21, 0.4,         0.99        ), 0.4,     ],
            [new HSV(3,    pi()/2/pi(), 1           ), 0.5,     ],
            [new HSV(pi(), 1,           0.0         ), 1.0      ],
            [new HSV(pi(), sqrt(0.7),   1           ), sqrt(0.7)],
            ];
    }

    /**
     * @dataProvider getSaturationProvider
     */
    public function testGetSaturation($c, $h)
    {
        $this->assertEquals($h, $c->saturation,      '', self::comparison_precision);
        $this->assertEquals($h, $c->getSaturation(), '', self::comparison_precision);
    }

    public function setSaturationProvider()
    {
        return [
            [0,           0.0,     ],
            [0.0,         0.0,     ],
            [0.4,         0.4,     ],
            [pi()/2/pi(), 0.5,     ],
            [1,           1.0      ],
            [sqrt(0.7),   sqrt(0.7)],
            ];
    }

    /**
     * @dataProvider setSaturationProvider
     */
    public function testSetSaturation($s, $g)
    {
        $c = new HSV(0, 0, 0);
        $c->setSaturation($s);
        $this->assertEquals($g, $c->saturation, '', self::comparison_precision);

        $c = new HSV(0, 0, 0);
        $c->saturation = $s;
        $this->assertEquals($g, $c->saturation, '', self::comparison_precision);
    }

    public function getValueProvider()
    {
        return [
            [new HSV(0,    0.0,  0          ), 0.0      ],
            [new HSV(0.0,  0,    0.0        ), 0.0      ],
            [new HSV(2.21, 0.3,  0.4        ), 0.4      ],
            [new HSV(3,    1.0,  pi()/2/pi()), 0.5      ],
            [new HSV(pi(), 0.4,  1          ), 1.0      ],
            [new HSV(pi(), 1,    sqrt(0.7)  ), sqrt(0.7)],
            ];
    }

    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue($c, $h)
    {
        $this->assertEquals($h, $c->value,      '', self::comparison_precision);
        $this->assertEquals($h, $c->getValue(), '', self::comparison_precision);
    }

    public function setValueProvider()
    {
        return [
            [0,           0.0,     ],
            [0.0,         0.0,     ],
            [0.4,         0.4,     ],
            [pi()/2/pi(), 0.5,     ],
            [1,           1.0      ],
            [sqrt(0.7),   sqrt(0.7)],
            ];
    }

    /**
     * @dataProvider setValueProvider
     */
    public function testSetValue($s, $g)
    {
        $c = new HSV(0, 0, 0);
        $c->setValue($s);
        $this->assertEquals($g, $c->value, '', self::comparison_precision);

        $c = new HSV(0, 0, 0);
        $c->value = $s;
        $this->assertEquals($g, $c->value, '', self::comparison_precision);
    }

    public function getTupleProvider()
    {
        return [
            [new HSV(0,    0,   0.0          ), [0.0,  0.0, 0.0            ]],
            [new HSV(0.0,  0.0, 0.0          ), [0.0,  0.0, 0.0            ]],
            [new HSV(2.21, 0.4, 0.99         ), [2.21, 0.4, 0.99           ]],
            [new HSV(3,    0.3, 1            ), [3.0,  0.3, 1.0            ]],
            [new HSV(pi(), 0.7, 0.0          ), [pi(), 0.7, 0.0            ]],
            [new HSV(sqrt(pi()), sqrt(0.7), 1), [sqrt(pi()), sqrt(0.7), 1.0]],
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
}

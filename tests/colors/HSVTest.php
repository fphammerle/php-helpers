<?php

namespace fphammerle\helpers\tests\colors;

use \fphammerle\helpers\colors\HSV;
use \fphammerle\helpers\colors\RGB;

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

    public function toRGBProvider()
    {
        return [
            [new HSV(0.0,       0.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(0.0,       0.0, 1/4), new RGB(1/4, 1/4, 1/4)],
            [new HSV(0.0,       0.0, 1.0), new RGB(1.0, 1.0, 1.0)],
            [new HSV(0.0,       1.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(0.0,       1/5, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(1.0,       0.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(1.0,       0.0, 1/4), new RGB(1/4, 1/4, 1/4)],
            [new HSV(1.0,       0.0, 1.0), new RGB(1.0, 1.0, 1.0)],
            [new HSV(1.0,       1.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(1.0,       1/5, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi()*3/2,  0.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi()*3/2,  0.0, 1/4), new RGB(1/4, 1/4, 1/4)],
            [new HSV(pi()*3/2,  0.0, 1.0), new RGB(1.0, 1.0, 1.0)],
            [new HSV(pi()*3/2,  1.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi()*3/2,  1/5, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi(),      0.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi(),      0.0, 1/4), new RGB(1/4, 1/4, 1/4)],
            [new HSV(pi(),      0.0, 1.0), new RGB(1.0, 1.0, 1.0)],
            [new HSV(pi(),      1.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi(),      1/5, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi()/2,    0.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi()/2,    0.0, 1/4), new RGB(1/4, 1/4, 1/4)],
            [new HSV(pi()/2,    0.0, 1.0), new RGB(1.0, 1.0, 1.0)],
            [new HSV(pi()/2,    1.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi()/2,    1/5, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi()/6*0,  1.0, 1.0), new RGB(1.0, 0.0, 0.0)],
            [new HSV(pi()/6*0,  1.0, 0.6), new RGB(0.6, 0.0, 0.0)],
            [new HSV(pi()/6*0,  1.0, 0.2), new RGB(0.2, 0.0, 0.0)],
            [new HSV(pi()/6*0,  1.0, 0.0), new RGB(0.0, 0.0, 0.0)],
            [new HSV(pi()/6*1,  1.0, 1.0), new RGB(1.0, 0.5, 0.0)],
            [new HSV(pi()/6*1,  1.0, 0.6), new RGB(0.6, 0.3, 0.0)],
            [new HSV(pi()/6*1,  1.0, 0.2), new RGB(1/5, 0.1, 0.0)],
            [new HSV(pi()/6*2,  1.0, 1.0), new RGB(1.0, 1.0, 0.0)],
            [new HSV(pi()/6*2,  1.0, 0.6), new RGB(0.6, 0.6, 0.0)],
            [new HSV(pi()/6*2,  1.0, 0.2), new RGB(0.2, 0.2, 0.0)],
            [new HSV(pi()/6*3,  1.0, 1.0), new RGB(0.5, 1.0, 0.0)],
            [new HSV(pi()/6*3,  1.0, 0.6), new RGB(0.3, 0.6, 0.0)],
            [new HSV(pi()/6*3,  1.0, 0.2), new RGB(0.1, 0.2, 0.0)],
            [new HSV(pi()/6*4,  1.0, 1.0), new RGB(0.0, 1.0, 0.0)],
            [new HSV(pi()/6*4,  1.0, 0.6), new RGB(0.0, 0.6, 0.0)],
            [new HSV(pi()/6*4,  1.0, 0.2), new RGB(0.0, 0.2, 0.0)],
            [new HSV(pi()/6*5,  1.0, 1.0), new RGB(0.0, 1.0, 0.5)],
            [new HSV(pi()/6*5,  1.0, 0.6), new RGB(0.0, 0.6, 0.3)],
            [new HSV(pi()/6*5,  1.0, 0.2), new RGB(0.0, 0.2, 0.1)],
            [new HSV(pi()/6*6,  1.0, 1.0), new RGB(0.0, 1.0, 1.0)],
            [new HSV(pi()/6*6,  1.0, 0.6), new RGB(0.0, 0.6, 0.6)],
            [new HSV(pi()/6*6,  1.0, 0.2), new RGB(0.0, 0.2, 0.2)],
            [new HSV(pi()/6*7,  1.0, 1.0), new RGB(0.0, 0.5, 1.0)],
            [new HSV(pi()/6*7,  1.0, 0.6), new RGB(0.0, 0.3, 0.6)],
            [new HSV(pi()/6*7,  1.0, 0.2), new RGB(0.0, 0.1, 0.2)],
            [new HSV(pi()/6*8,  1.0, 1.0), new RGB(0.0, 0.0, 1.0)],
            [new HSV(pi()/6*8,  1.0, 0.6), new RGB(0.0, 0.0, 0.6)],
            [new HSV(pi()/6*8,  1.0, 0.2), new RGB(0.0, 0.0, 0.2)],
            [new HSV(pi()/6*9,  1.0, 1.0), new RGB(0.5, 0.0, 1.0)],
            [new HSV(pi()/6*9,  1.0, 0.6), new RGB(0.3, 0.0, 0.6)],
            [new HSV(pi()/6*9,  1.0, 0.2), new RGB(0.1, 0.0, 0.2)],
            [new HSV(pi()/6*10, 1.0, 1.0), new RGB(1.0, 0.0, 1.0)],
            [new HSV(pi()/6*10, 1.0, 0.6), new RGB(0.6, 0.0, 0.6)],
            [new HSV(pi()/6*10, 1.0, 0.2), new RGB(0.2, 0.0, 0.2)],
            [new HSV(pi()/6*11, 1.0, 1.0), new RGB(1.0, 0.0, 0.5)],
            [new HSV(pi()/6*11, 1.0, 0.6), new RGB(0.6, 0.0, 0.3)],
            [new HSV(pi()/6*11, 1.0, 0.2), new RGB(0.2, 0.0, 0.1)],
            [new HSV(deg2rad(10),  0.8, 0.4), new RGB(102/255,     34/255,      20.4/255   )],
            [new HSV(deg2rad(35),  0.8, 0.4), new RGB(102/255,     68/255,      20.4/255   )],
            [new HSV(deg2rad(35),  0.3, 0.6), new RGB(153/255,     133.875/255, 107.1/255  )],
            [new HSV(deg2rad(100), 0.3, 0.6), new RGB(122.4/255,   153/255,     107.1/255  )],
            [new HSV(deg2rad(130), 0.6, 0.2), new RGB(20.4/255,    51/255,      25.5/255   )],
            [new HSV(deg2rad(155), 0.6, 0.9), new RGB(91.8/255,    229.5/255,   172.125/255)],
            [new HSV(deg2rad(200), 0.8, 0.6), new RGB(30.6/255,    112.2/255,   153/255    )],
            [new HSV(deg2rad(226), 0.8, 0.6), new RGB(30.6/255,    59.16/255,   153/255    )],
            [new HSV(deg2rad(226), 0.1, 0.5), new RGB(114.75/255,  117.725/255, 127.5/255  )],
            [new HSV(deg2rad(250), 0.9, 0.9), new RGB(57.373/255,  22.95/255,   229.5/255  )],
            [new HSV(deg2rad(270), 0.9, 0.9), new RGB(126.225/255, 22.95/255,   229.5/255  )],
            [new HSV(deg2rad(295), 0.7, 0.9), new RGB(216.11/255,  68.85/255,   229.5/255  )],
            [new HSV(deg2rad(322), 0.7, 0.9), new RGB(229.5/255,   68.85/255,   170.595/255)],
            [new HSV(deg2rad(322), 0.8, 0.4), new RGB(102/255,     20.4/255,    72.08/255  )],
            [new HSV(deg2rad(340), 0.8, 0.4), new RGB(102/255,     20.4/255,    47.6/255   )],
            [new HSV(deg2rad(359), 0.8, 0.4), new RGB(102/255,     20.4/255,    21.76/255  )],
            [new HSV(deg2rad(359), 0.5, 0.1), new RGB(25.5/255,    12.75/255,   12.96/255  )],
            ];
    }

    /**
     * @dataProvider toRGBProvider
     */
    public function testToRGB($hsv, $rgb_e)
    {
        $rgb_r = $hsv->toRGB();
        $this->assertTrue(
            $rgb_e->equals($rgb_r),
            sprintf(
                "\$hsv = %s\n\$hsv->toRGB() = %s\n\$rgb_e = %s",
                print_r($hsv, true),
                print_r($rgb_r, true),
                print_r($rgb_e, true)
                )
            );
    }
}
